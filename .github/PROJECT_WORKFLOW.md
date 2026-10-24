# Project status automation

This workflow maintains the LIAP ecosystem project at
https://github.com/users/imdhemy/projects/2.

## Setup

Before merging, configure the `LIAP_PROJECT_TOKEN` Actions secret in all three
repositories. Use a token that can write this user project, read all three
repositories and their issue/PR relationships, and write issue labels. For a
classic personal access token, GitHub documents the `project` and `repo` scopes
for user-project automation. Do not commit the token or paste it into issues.
See [GitHub's authentication guidance](https://docs.github.com/en/issues/planning-and-tracking-with-projects/automating-your-project/automating-projects-using-actions).

The default `GITHUB_TOKEN` cannot write user projects. Missing project credentials
produce an explicit workflow failure rather than silently pretending to sync.

## Rules

- New and reopened issues receive `needs triage` and no project status.
- Assigning an issue moves it to In progress. Unassignment does not change status.
- Only closing PR relationships count; ordinary references do not complete work.
- An open draft PR moves its closing issues to In progress; a ready PR moves them
  to In review. Review revisions stay In review until the PR returns to draft.
- A merged PR marks its closing leaf issues Done. Parent issues need all their
  sub-issues completed. A PR closed without merging does not complete its issues.
- Closing an issue as completed sets Done. Not-planned/duplicate closures clear
  status. Backlog and Ready remain maintainer decisions.
- Parents become Done only after every leaf is closed as completed or has reached
  Done through a closing PR. Not-planned/duplicate leaves never count. Nested parent
  issues can remain open; the workflow changes project status, not issue state.
  Parents awaiting triage are not automatically completed. Reopening a sub-issue
  clears a parent's Done status if its hierarchy is no longer complete.
- LIAP also reconciles parents every 30 minutes and on manual dispatch, catching
  sub-issue additions/removals. Leaf statuses are not periodically overwritten.

The existing project Status options are matched by name with their leading emoji
ignored. This workflow is restricted to the three `imdhemy` package repositories.
It does not import historical issues or infer Backlog/Ready from labels.

The focused mocked-event tests can be run with
`node --test .github/scripts/project-status.test.cjs`. They do not call GitHub or
need credentials. Live event verification still requires project access.

## Project built-in workflows

Auto-add of open issues is enabled per repository, together with auto-add of
sub-issues. The broad Item closed workflow must remain off: it does not distinguish
completed from not-planned closures. Pull request merged may remain enabled for
PR cards. Leave Item reopened, PR-linked status changes, and automatic status-based
issue closing disabled; this script owns the more specific issue transitions.

## Execution boundary

The workflow uses `pull_request_target` only for metadata. Checkout is explicitly
the repository's default branch with persisted credentials disabled; PR-head code,
PR-body commands and artifacts are never executed. Both actions are pinned to
verified release commits. The project token is passed only to the trusted script.
