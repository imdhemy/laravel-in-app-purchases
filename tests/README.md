# Tests

When we started writing this test suite, me (the maintainer) was NOT
knowledgeable about testing a remote API. I used real APIs to test where I
should mock responses instead.

Starting from version `1.x`, I just replaced the real API calls with mocks,
just to make sure that the tests are still passing.

Over years, I learned more about testing, and I'm practicing it every day. I
decided to re-write the test suite with the following goals:

- Two test suites, Unit and Integration.
- Not an overnight complete rewrite, but a rewrite that is incremental.
- At the end, we should have two directories under `tests`: `Unit` and
  `Integration`.
- `Unit` should reflect the same structure as the `src` directory.
- `Integration` should describe the features provided by the package.
