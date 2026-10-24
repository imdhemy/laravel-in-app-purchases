'use strict';

const OWNER = 'imdhemy';
const REPOSITORIES = new Set(['laravel-in-app-purchases', 'google-play-billing', 'appstore-iap']);
const ISSUE_FIELDS = 'id number state stateReason repository { name owner { login } } parent { id }';

module.exports = async function syncProject({ github, context, core }) {
  const { user } = await github.graphql(`query {
    user(login: "${OWNER}") { projectV2(number: 2) {
      id fields(first: 100) { nodes {
        ... on ProjectV2SingleSelectField { id name options { id name } }
      } }
    } }
  }`);
  const project = user.projectV2;
  if (!project) throw new Error('LIAP project #2 is not accessible.');
  const field = project.fields.nodes.find(value => value.name === 'Status');
  if (!field) throw new Error('The project needs a Status single-select field.');
  const normalize = name => name.replace(/^[^a-z]+/i, '').trim().toLowerCase();
  const options = new Map(field.options.map(value => [normalize(value.name), value.id]));
  for (const name of ['in progress', 'in review', 'done']) {
    if (!options.has(name)) throw new Error(`Missing Status option: ${name}`);
  }
  const inScope = issue => issue?.repository?.owner?.login === OWNER && REPOSITORIES.has(issue.repository.name);

  async function connection(query, variables, select) {
    const nodes = [];
    let cursor = null;
    do {
      const result = select(await github.graphql(query, { ...variables, cursor }));
      if (!result) throw new Error('A required GitHub connection is not accessible.');
      nodes.push(...result.nodes);
      cursor = result.pageInfo.hasNextPage ? result.pageInfo.endCursor : null;
    } while (cursor);
    return nodes;
  }

  async function readIssue(id) {
    const { node } = await github.graphql(`query($id: ID!) {
      node(id: $id) { ... on Issue { ${ISSUE_FIELDS} } }
    }`, { id });
    if (!node?.id) throw new Error(`Issue ${id} is not accessible.`);
    return node;
  }

  async function children(id) {
    return connection(`query($id: ID!, $cursor: String) {
      node(id: $id) { ... on Issue { subIssues(first: 100, after: $cursor) {
        nodes { ${ISSUE_FIELDS} } pageInfo { hasNextPage endCursor }
      } } }
    }`, { id }, result => result.node?.subIssues);
  }

  async function projectItem(id) {
    const items = await connection(`query($id: ID!, $cursor: String) {
      node(id: $id) { ... on Issue { projectItems(first: 100, after: $cursor) {
        nodes { id project { id } fieldValueByName(name: "Status") {
          ... on ProjectV2ItemFieldSingleSelectValue { optionId }
        } } pageInfo { hasNextPage endCursor }
      } } }
    }`, { id }, result => result.node?.projectItems);
    return items.find(item => item.project.id === project.id);
  }

  async function setStatus(issue, name, add = true) {
    if (!inScope(issue)) return;
    let item = await projectItem(issue.id);
    if (!item && add) {
      const result = await github.graphql(`mutation($project: ID!, $issue: ID!) {
        addProjectV2ItemById(input: { projectId: $project, contentId: $issue }) { item { id } }
      }`, { project: project.id, issue: issue.id });
      item = result.addProjectV2ItemById.item;
    }
    if (!item) return;
    const desired = name ? options.get(name) : null;
    if (desired === (item.fieldValueByName?.optionId ?? null)) return;
    if (name) {
      await github.graphql(`mutation($project: ID!, $item: ID!, $field: ID!, $option: String!) {
        updateProjectV2ItemFieldValue(input: {
          projectId: $project, itemId: $item, fieldId: $field,
          value: { singleSelectOptionId: $option }
        }) { projectV2Item { id } }
      }`, { project: project.id, item: item.id, field: field.id, option: desired });
    } else {
      await github.graphql(`mutation($project: ID!, $item: ID!, $field: ID!) {
        clearProjectV2ItemFieldValue(input: {
          projectId: $project, itemId: $item, fieldId: $field
        }) { projectV2Item { id } }
      }`, { project: project.id, item: item.id, field: field.id });
    }
    core.info(`${issue.repository.name}#${issue.number}: ${name || 'no status'}`);
  }

  async function completed(issue, path = new Set()) {
    if (path.has(issue.id)) throw new Error('Cycle in the sub-issue hierarchy.');
    const next = new Set([...path, issue.id]);
    const nested = await children(issue.id);
    if (nested.length) {
      if (issue.state === 'CLOSED' && issue.stateReason !== 'COMPLETED') return false;
      for (const child of nested) if (!await completed(child, next)) return false;
      return true;
    }
    if (issue.state === 'CLOSED') return issue.stateReason === 'COMPLETED';
    const item = await projectItem(issue.id);
    return item?.fieldValueByName?.optionId === options.get('done');
  }

  async function syncParent(issue) {
    if (!inScope(issue)) return;
    const nested = await children(issue.id);
    if (!nested.length) return;
    let allCompleted = !(issue.state === 'CLOSED' && issue.stateReason !== 'COMPLETED');
    for (const child of nested) {
      if (!await completed(child)) { allCompleted = false; break; }
    }
    if (allCompleted) {
      // A reopened parent stays in triage until a maintainer reassesses it.
      const labels = await github.paginate(github.rest.issues.listLabelsOnIssue, {
        owner: OWNER, repo: issue.repository.name, issue_number: issue.number, per_page: 100,
      });
      if (!labels.some(label => label.name === 'needs triage')) await setStatus(issue, 'done');
    } else {
      const item = await projectItem(issue.id);
      if (item?.fieldValueByName?.optionId === options.get('done')) await setStatus(issue, null, false);
    }
  }

  async function syncAncestors(issue) {
    const seen = new Set([issue.id]);
    let id = issue.parent?.id;
    while (id) {
      if (seen.has(id)) throw new Error('Cycle in the parent hierarchy.');
      seen.add(id);
      const parent = await readIssue(id);
      if (!inScope(parent)) break;
      await syncParent(parent);
      id = parent.parent?.id;
    }
  }

  async function triage(issue) {
    await github.rest.issues.addLabels({
      owner: OWNER, repo: issue.repository.name, issue_number: issue.number, labels: ['needs triage'],
    });
    await setStatus(issue, null);
  }

  if (context.eventName === 'issues') {
    const issue = await readIssue(context.payload.issue.node_id);
    if (!inScope(issue)) return;
    const action = context.payload.action;
    if (issue.state === 'CLOSED') {
      if (issue.stateReason === 'COMPLETED' && (await children(issue.id)).length) {
        await syncParent(issue);
      } else {
        await setStatus(issue, issue.stateReason === 'COMPLETED' ? 'done' : null);
      }
    } else if (action === 'opened' || action === 'reopened') {
      await triage(issue);
    } else if (action === 'assigned') {
      await setStatus(issue, 'in progress');
    }
    await syncAncestors(issue);
    return;
  }

  if (context.eventName === 'pull_request_target') {
    const id = context.payload.pull_request.node_id;
    const { node: pr } = await github.graphql(`query($id: ID!) {
      node(id: $id) { ... on PullRequest { state isDraft merged } }
    }`, { id });
    if (!pr || (pr.state === 'CLOSED' && !pr.merged)) return;
    const linked = await connection(`query($id: ID!, $cursor: String) {
      node(id: $id) { ... on PullRequest { closingIssuesReferences(first: 100, after: $cursor) {
        nodes { ${ISSUE_FIELDS} } pageInfo { hasNextPage endCursor }
      } } }
    }`, { id }, result => result.node?.closingIssuesReferences);
    for (const issue of linked) {
      if (!inScope(issue)) continue;
      if (issue.state === 'CLOSED' && issue.stateReason !== 'COMPLETED') continue;
      if (pr.merged) {
        if ((await children(issue.id)).length) await syncParent(issue);
        else await setStatus(issue, 'done');
      } else if (issue.state === 'OPEN') {
        await setStatus(issue, pr.isDraft ? 'in progress' : 'in review');
      }
      await syncAncestors(issue);
    }
    return;
  }

  // The scheduled/manual pass repairs hierarchy changes without overwriting leaf statuses.
  const items = await connection(`query($project: ID!, $cursor: String) {
    node(id: $project) { ... on ProjectV2 { items(first: 100, after: $cursor) {
      nodes { content { ... on Issue { ${ISSUE_FIELDS} } } }
      pageInfo { hasNextPage endCursor }
    } } }
  }`, { project: project.id }, result => result.node?.items);
  for (const item of items) if (inScope(item.content)) await syncParent(item.content);
};
