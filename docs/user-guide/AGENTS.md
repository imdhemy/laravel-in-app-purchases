# Package user documentation

These instructions apply to authoring package-user documentation in this directory. They are editorial guidance, not application architecture requirements. Explicit user instructions take precedence.

## Audience and purpose

Write for developers integrating this package into their applications. Help them complete a task using the released API. The package provides framework integration while billing operations and native provider models belong to the underlying packages.

Keep architecture hypotheses and experiments in `docs/design-notebook/`. Do not repeat design discussions, implementation history, or internal construction details in user guides.

## Organization

- Organize guides by provider, then responsibility. Use readable page titles and kebab-case paths, for example `google-play/purchase/subscription-purchases.md`.
- Distinguish subscription purchases from subscription catalogue entries.
- Add pages for implemented capabilities only. Do not create placeholder pages for planned APIs or imply that all v2 services are supported.
- Begin with a concise capability page. Extract common provider setup when repetition justifies a getting-started page.
- Keep upgrade and deprecation guidance separate from ordinary usage, with links between them where needed.
- Keep this file out of end-user navigation.

## Writing style

- Use task-first headings, such as “Retrieving a subscription purchase”.
- Introduce the task with one short sentence, followed by the smallest useful example.
- Explain the result immediately after the example, including the native return type.
- Show configured defaults before customization. Explain application overrides and custom clients separately.
- Give each example one purpose. Do not combine setup, overrides, error handling, and response inspection in an introductory example.
- Include imports when introducing an entry point. Use meaningful variables such as `$purchaseToken` and `$purchase`.
- Explain observable behavior, requirements, and consequences. Do not narrate every line of code.
- Use direct, concise language. Avoid promotional claims, unnecessary jargon, and architectural justification.

## Capability page structure

Use the following progression, omitting sections that are not relevant:

1. Introduction and prerequisites.
2. Configuration and authentication.
3. The main task using configured defaults.
4. Selecting another application.
5. Providing a custom client.
6. Working with the returned model.
7. Errors and relevant limitations.

This is a progression, not a requirement to create empty headings or repeat setup on every page.

## API accuracy

- Check the actual public API and supported dependency versions before writing examples. Do not invent methods, signatures, configuration keys, or release versions.
- Document required inputs, optional defaults, overrides, return types, and failure behavior.
- Explain that a custom client must provide the authentication required by the provider API.
- Where applicable, state that separate facade chains have independent settings and retained operation instances keep their settings. Do not require users to understand container bindings or facade caching.
- Distinguish retrieving a purchase from deciding entitlement. Do not imply that successful retrieval alone grants access; state and relevant line items matter.
- State supported operations explicitly. Future coverage belongs in roadmap material, not current capability claims.
- Do not assume framework features apply to delegated services. Verify compatibility before documenting testing tools, client interception, or lifecycle behavior.

## Documentation boundaries

Explain LIAP configuration, fluent overrides, operation behavior, and how users receive native provider models. Link to the underlying package for model APIs and to official provider documentation for field semantics, permissions, and restrictions. Do not duplicate complete upstream schemas.

Treat these as package-specific editorial conventions, not requirements imposed by an external framework or provider.

## Review

Check examples against the implementation and dependency versions. Keep checks proportionate to the change. Follow the user's validation instructions; do not run commands unless authorized. Do not publish documentation or change unrelated code as part of writing a guide.

