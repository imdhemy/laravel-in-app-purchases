# Provider-specific Laravel integration

Date: 2026-10-23
Status: experiment

## Hypothesis

LIAP can provide Laravel ecosystem compatibility while delegating billing operations and provider models to the underlying packages. Separate provider namespaces make ownership explicit and allow each integration to evolve independently.

## What we did

Introduced Google Play subscription purchase retrieval under `GooglePlay\Purchase`, with a dedicated facade namespace. Configuration supplies optional defaults; fluent overrides belong to an individual operation. Transient resolution and an uncached facade isolate separate calls. The existing API remains available, with legacy Google retrieval deprecated.

## What we expect

Less duplicated billing logic, clearer provider boundaries, and Laravel-friendly access without a unified billing model. The approach should accommodate additional provider capabilities without requiring shared abstractions in advance. Shared configuration design and legacy removal remain open decisions.
