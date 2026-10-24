'use strict';

const { test } = require('node:test');
const assert = require('node:assert/strict');
const sync = require('./project-status.cjs');

function harness(event, action, initialStatus = null) {
  const issue = (id, overrides = {}) => ({
    id, number: Number(id), state: 'OPEN', stateReason: null,
    repository: { name: 'google-play-billing', owner: { login: 'imdhemy' } },
    parent: null, children: [], labels: [], ...overrides,
  });
  const issues = new Map([['1', issue('1')]]);
  const statuses = new Map([['1', initialStatus]]);
  const pr = { state: 'OPEN', isDraft: false, merged: false, linked: ['1'] };
  const page = nodes => ({ nodes, pageInfo: { hasNextPage: false, endCursor: null } });
  const item = id => ({
    id, project: { id: 'project' },
    fieldValueByName: statuses.get(id) ? { optionId: statuses.get(id) } : null,
  });
  const github = {
    rest: { issues: {
      listLabelsOnIssue: Symbol('labels'),
      addLabels: async ({ issue_number, labels }) => {
        issues.get(String(issue_number)).labels.push(...labels);
      },
    } },
    paginate: async (_method, { issue_number }) =>
      issues.get(String(issue_number)).labels.map(name => ({ name })),
    graphql: async (query, variables = {}) => {
      if (query.includes('user(login:')) return { user: { projectV2: {
        id: 'project', fields: { nodes: [{ id: 'status', name: 'Status', options: [
          { id: 'progress', name: '🏗 In progress' },
          { id: 'review', name: '👀 In review' },
          { id: 'done', name: '✅ Done' },
        ] }] },
      } } };
      if (query.includes('clearProjectV2ItemFieldValue')) {
        statuses.set(variables.item, null); return {};
      }
      if (query.includes('updateProjectV2ItemFieldValue')) {
        statuses.set(variables.item, variables.option); return {};
      }
      if (query.includes('addProjectV2ItemById')) {
        statuses.set(variables.issue, null);
        return { addProjectV2ItemById: { item: item(variables.issue) } };
      }
      if (query.includes('closingIssuesReferences')) return {
        node: { closingIssuesReferences: page(pr.linked.map(id => issues.get(id))) },
      };
      if (query.includes('... on PullRequest')) return { node: pr };
      if (query.includes('projectItems')) return {
        node: { projectItems: page(statuses.has(variables.id) ? [item(variables.id)] : []) },
      };
      if (query.includes('subIssues')) return {
        node: { subIssues: page(issues.get(variables.id).children.map(id => issues.get(id))) },
      };
      return { node: issues.get(variables.id) };
    },
  };
  const context = { eventName: event, payload: {
    action, issue: { node_id: '1' }, pull_request: { node_id: 'pr' },
  } };
  return {
    issues, statuses, pr,
    add: (id, overrides) => issues.set(id, issue(id, overrides)),
    run: () => sync({ github, context, core: { info() {} } }),
  };
}

test('assignment starts work; unassignment preserves its current stage', async () => {
  const assigned = harness('issues', 'assigned');
  await assigned.run();
  assert.equal(assigned.statuses.get('1'), 'progress');
  const unassigned = harness('issues', 'unassigned', 'review');
  await unassigned.run();
  assert.equal(unassigned.statuses.get('1'), 'review');
});

test('new and reopened issues have no status and need triage', async () => {
  for (const action of ['opened', 'reopened']) {
    const h = harness('issues', action, 'done');
    await h.run();
    assert.equal(h.statuses.get('1'), null);
    assert.ok(h.issues.get('1').labels.includes('needs triage'));
  }
});

test('only completed closures count as Done', async () => {
  for (const reason of ['COMPLETED', 'NOT_PLANNED', 'DUPLICATE']) {
    const h = harness('issues', 'closed', 'done');
    Object.assign(h.issues.get('1'), { state: 'CLOSED', stateReason: reason });
    await h.run();
    assert.equal(h.statuses.get('1'), reason === 'COMPLETED' ? 'done' : null);
  }
});

test('ready and draft PRs change only their closing issues', async () => {
  for (const isDraft of [true, false]) {
    const h = harness('pull_request_target', 'edited');
    h.add('2'); h.statuses.set('2', 'progress');
    h.pr.isDraft = isDraft;
    await h.run();
    assert.equal(h.statuses.get('1'), isDraft ? 'progress' : 'review');
    assert.equal(h.statuses.get('2'), 'progress');
  }
});

test('merged PRs complete work; abandoned PRs do not', async () => {
  for (const merged of [true, false]) {
    const h = harness('pull_request_target', 'closed', 'review');
    Object.assign(h.pr, { state: merged ? 'MERGED' : 'CLOSED', merged });
    await h.run();
    assert.equal(h.statuses.get('1'), merged ? 'done' : 'review');
  }
});

test('a not-planned sibling prevents parent completion', async () => {
  const h = harness('issues', 'closed');
  Object.assign(h.issues.get('1'), { state: 'CLOSED', stateReason: 'COMPLETED', parent: { id: '3' } });
  h.add('2', { state: 'CLOSED', stateReason: 'NOT_PLANNED' });
  h.add('3', { children: ['1', '2'] }); h.statuses.set('3', 'done');
  await h.run();
  assert.equal(h.statuses.get('3'), null);
});

test('the final completed child completes its parent without closing it', async () => {
  const h = harness('issues', 'closed');
  Object.assign(h.issues.get('1'), { state: 'CLOSED', stateReason: 'COMPLETED', parent: { id: '3' } });
  h.add('2', { state: 'CLOSED', stateReason: 'COMPLETED' });
  h.add('3', { children: ['1', '2'] });
  await h.run();
  assert.equal(h.statuses.get('3'), 'done');
  assert.equal(h.issues.get('3').state, 'OPEN');
});
