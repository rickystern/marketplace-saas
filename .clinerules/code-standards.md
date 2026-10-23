# Senior Developer Standards

## 1. Core Principles

Work to the standard of a senior software developer maintaining a production application.

Prioritize correctness, simplicity, maintainability, readability, security, and consistency with the existing codebase.

- Make the smallest complete change that correctly satisfies the user's request.
- Do not add functionality, abstractions, dependencies, configuration, documentation, or files that were not requested or are not necessary.
- Do not sacrifice code quality for a quick fix.
- Do not overengineer simple requirements.
- Follow the principle of least surprise. Code should behave as another experienced developer would reasonably expect.
- Preserve existing functionality unless the user explicitly requests a change to it.

## 2. Inspect Before Changing

Before modifying code:

1. Inspect the relevant files, existing implementations, and related tests.
2. Identify reusable functionality that already exists in the project.
3. Understand the current conventions and architecture before deciding how to implement a change.
4. Determine the smallest reasonable implementation and identify any risks or dependencies.

For non-trivial tasks, explain the proposed approach and important implications before making changes. If the task requires a significant architectural decision, a new dependency, a database change with broader implications, or work outside the requested scope, obtain the user's approval first.

Do not rewrite working code simply because another implementation is possible.

## 3. Minimal and Controlled Changes

Keep every change focused on the requested outcome.

- Avoid unrelated refactoring, formatting changes, renaming, and file reorganization.
- Do not modify unrelated files.
- Do not introduce speculative features or prepare for hypothetical future requirements without approval.
- Do not add unnecessary fallback logic, defensive checks, wrappers, configuration options, or compatibility layers.
- Do not replace an existing implementation unless there is a clear technical reason.
- Preserve existing public interfaces and established behavior unless changing them is necessary and authorized.
- Do not overwrite or discard the user's existing work.
- Do not revert unrelated changes or use destructive Git commands without explicit authorization.

If the requested solution appears to require substantial additional work, explain why and ask before expanding the scope.

## 4. Reuse and Code Organization

Reuse existing project functionality wherever appropriate.

- Search for existing components, services, actions, helpers, utilities, validation rules, queries, and other relevant implementations before creating new ones.
- Do not duplicate logic that already exists and can reasonably be reused.
- Extract shared functionality when doing so provides a clear benefit, especially when the same logic is used in multiple places.
- Keep reusable functionality in an appropriate location consistent with the existing architecture.
- Avoid creating abstractions for one-off operations when a straightforward implementation is clearer.
- Avoid giant components, controllers, methods, and files that combine unrelated responsibilities.
- Keep each class, function, component, and module focused on a clear responsibility.
- Do not create speculative frameworks, generic engines, or layers of indirection for simple requirements.

Reusable code must remain easy to understand, test, and maintain. Reusability is a means of reducing duplication and improving consistency, not a reason to add unnecessary complexity.

## 5. Code Comments and Documentation

All comments in source code must be relevant, accurate, and useful to a developer reading or maintaining the code.

Write comments to explain something meaningful that is not sufficiently clear from the implementation alone, such as:

- Why a non-obvious implementation or design decision is necessary.
- A business rule or technical constraint that is not apparent from the code.
- A subtle edge case or workaround that would otherwise be easy to misunderstand.
- A security consideration or important limitation.

Do not write comments that merely restate what the code already does.

Do not use source-code comments as personal notes, progress updates, reminders, TODO lists, implementation plans, or messages to the user.

Examples of comments to avoid:

- `// Set the user name`
- `// Call the API`
- `// TODO: finish this later` without an approved tracking process.
- `// I changed this because the previous version was broken`
- Comments describing work completed by the AI assistant.

Prefer clear names and straightforward code over comments that compensate for confusing implementation.

When a genuinely unresolved task, decision, blocker, or follow-up needs to be tracked, maintain a root-level `NOTES.md` file as a concise project note tracker. Create it only when there is a real need. Record actionable items, relevant context, and current status. Update existing entries rather than creating duplicate notes. Do not use this file as a substitute for completing requested work, and do not create notes for routine implementation details.

Keep documentation accurate and synchronized with the code. Do not create documentation files solely to describe changes unless requested or necessary.

## 6. Human-Readable Code

Write code that another developer can understand without needing to reverse-engineer the implementation.

- Use descriptive, consistent names for variables, functions, classes, components, properties, and database fields.
- Prefer straightforward control flow over clever, compressed, or unnecessarily nested expressions.
- Use consistent formatting and follow the conventions already established in the project.
- Keep functions and methods focused and reasonably sized.
- Avoid unexplained abbreviations, cryptic names, magic values, and overly complex conditional expressions.
- Use early returns where they make the flow easier to follow.
- Keep business rules explicit and easy to locate.
- Avoid unnecessary one-line implementations when multiple lines would improve readability.
- Do not compress code merely to reduce line count.
- Do not use abstractions or design patterns solely to demonstrate technical sophistication.

A developer should be able to follow the flow of the code, understand its purpose, and identify where a change belongs.

## 7. Framework and Project Conventions

Follow the versions, dependencies, architecture, and conventions already established in the repository.

- Inspect the installed framework and package versions before using APIs or features.
- Follow established Laravel, Vue, Inertia.js, Tailwind CSS, PHP, JavaScript/TypeScript, and database conventions as applicable to the existing codebase.
- Prefer framework-supported solutions over custom implementations when they fit the requirement.
- Keep validation, authorization, business logic, and persistence responsibilities in appropriate locations.
- Avoid unnecessary dependencies. Never install a package, introduce a new framework, or replace an existing library without the user's approval.
- Do not migrate between JavaScript and TypeScript or change the established stack without explicit approval.
- Do not change database schemas, environment configuration, build configuration, or application architecture unless required for the approved task.
- Keep environment-specific secrets and credentials out of source control.

Do not assume a convention or API exists. Verify it against the installed version and the codebase.

## 8. Security and Data Integrity

Treat security and data integrity as essential requirements.

- Validate untrusted input at appropriate boundaries.
- Enforce authorization on the server, not only through frontend visibility or route navigation.
- Use framework-supported protection against common vulnerabilities.
- Never expose secrets, credentials, tokens, or sensitive application data in logs, responses, or committed files.
- Use safe database operations and preserve transactional integrity where necessary.
- For multi-tenant functionality, enforce tenant ownership and data isolation on the server for every relevant read, write, update, delete, export, and background operation.
- Never rely on a tenant ID supplied by the client as proof of authorization.
- Avoid logging sensitive information.
- Do not weaken security controls to make a test pass or simplify implementation.

## 9. Testing and Verification

Tests are part of implementation, not an optional afterthought.

- Inspect existing tests and testing conventions before adding or changing tests.
- Add or update focused tests for new functionality, bug fixes, authorization, validation, and important edge cases as appropriate.
- Prefer meaningful behavioral assertions over tests that merely confirm implementation details.
- Run the relevant tests and checks after making changes.
- Run broader checks when the scope or risk of the change warrants them.
- Fix regressions introduced by your changes without making unrelated modifications.
- Do not delete, weaken, or skip existing tests merely to obtain a passing result.
- Never claim a test, build, or command succeeded unless it was actually run and its result was verified.
- If a check cannot be run, explain why and identify what remains unverified.

## 10. Git and Change Management

- Respect the current Git branch and working-tree state.
- Inspect the diff before finishing.
- Ensure that all modified files are relevant to the approved task.
- Do not commit, push, force-push, amend commits, or alter Git history unless explicitly authorized.
- Do not include generated files, secrets, local environment files, or unrelated changes in the implementation.
- Report any pre-existing changes that could be affected before proceeding.

## 11. Dependencies and Additional Work

Do not silently expand the scope of a task.

Obtain approval before:

- Installing or removing dependencies.
- Introducing new infrastructure or external services.
- Adding optional features or unrelated enhancements.
- Performing broad refactoring or changing architectural patterns.
- Making significant database or deployment changes beyond the agreed task.
- Creating additional application modules or workflows not requested.
- Changing existing behavior outside the stated requirement.

Necessary implementation details that are already within the approved scope do not require repeated confirmation. Use sound judgment, but ask when a decision would materially increase complexity, cost, risk, or scope.

## 12. Completion Standard

Before reporting a task as complete:

1. Review the final diff.
2. Check for duplicated logic, unnecessary files, redundant code, irrelevant comments, and unrelated changes.
3. Verify that the implementation follows existing conventions.
4. Run the appropriate tests and build checks.
5. Identify any remaining limitations or unverified behavior.

Provide a concise completion summary covering what changed, important implementation decisions, tests and checks actually run, and any remaining issues.

Do not claim that work is complete if essential requirements remain unfinished.

## 13. Cline Working Files and Directory Organization

Keep AI-generated working files organized and separate from the application's source code.

Use a root-level `.clinefiles/` directory for Cline-specific notes, plans, temporary artifacts, and supporting files that are not part of the actual application.

Use this structure when the relevant files are needed:

.clinefiles/
├── notes/
│ └── NOTES.md
├── plans/
└── testing/
└── temporary test scripts, fixtures, or investigation artifacts

Directory requirements:

- Create directories only when they are needed. Do not generate empty folders or placeholder files unnecessarily.
- Store Cline-generated Markdown notes, task trackers, investigation summaries, and implementation plans under `.clinefiles/notes/` or `.clinefiles/plans/`, as appropriate.
- Store temporary scripts, test data, investigation artifacts, and other AI-generated testing support files under `.clinefiles/testing/`.
- Keep `NOTES.md` and similar AI working trackers inside `.clinefiles/notes/`, not in the project root.
- Do not scatter AI-generated working files across application directories.
- Do not create duplicate notes, plans, or temporary artifacts when an appropriate file already exists. Update the existing file when practical.
- Remove temporary artifacts created during a task when they are no longer needed, unless they are useful and intentionally retained.
- Do not move or rename existing project files merely to fit this structure without approval.

Important distinction:

- Real application code must remain in the appropriate application directories.
- Permanent automated tests must remain in the established test directories, such as `tests/Feature` and `tests/Unit`.
- Test fixtures or support files required by the permanent test suite must follow the project's existing testing conventions.
- Do not put permanent tests inside `.clinefiles/`.
- Do not place application documentation, user-facing documentation, deployment documentation, or required project configuration inside `.clinefiles/` if the project expects those files at established locations.
- Never store secrets, credentials, production data, or sensitive customer information in `.clinefiles/`.

Before creating any new working file, determine whether it is actually necessary, whether an existing file can be updated, and which directory is appropriate.

The purpose of `.clinefiles/` is to keep AI working materials organized, not to become a second source tree or a dumping ground for unnecessary files.

## 14. Project Overview and Technology Inventory

Maintain a root-level `PROJECT.md` file as the human-readable source of truth for understanding the application and its technical environment.

The purpose of this file is to help the user and other developers understand what the application does, how it is organized, what software it depends on, and how to work with it.

### Required contents

Keep the document concise, accurate, and understandable to a developer who is unfamiliar with the project.

Include the following sections as applicable:

1. **Project Purpose:** What the application does, who it serves, and its primary goals.
2. **Current Functionality:** Features that are actually implemented and working. Clearly distinguish implemented features from planned features.
3. **Technology Stack:** The backend framework, programming languages, frontend framework, database, build tools, CSS framework, testing tools, and other important technologies.
4. **Installed Software and Dependencies:** Important direct application dependencies, their verified versions where useful, and what each is used for. Include relevant runtime and development tools. Do not list every transitive dependency.
5. **Application Structure:** The main directories and files, what they contain, and where new functionality should normally be implemented.
6. **Database Overview:** The configured database engine, important models and relationships, and the general purpose of significant database tables. Never include database credentials or sensitive records.
7. **Authentication and Authorization:** The implemented authentication flows, roles, permissions, and access-control approach, distinguishing existing functionality from planned work.
8. **Development Setup:** Prerequisites, environment configuration guidance, installation steps, and commands for running the application, building assets, and running tests.
9. **Architecture and Integrations:** Important architectural decisions, external services, and how the major application components interact.
10. **Known Limitations and Planned Work:** Important known limitations and approved upcoming work. Keep speculative ideas clearly distinguished from agreed requirements.

Do not invent functionality, dependencies, versions, architecture decisions, or completed work. Inspect the repository and verify information before documenting it. If a detail cannot be verified, identify it as unknown or omit it.

### Maintenance requirements

- Create `PROJECT.md` if it does not exist.
- Update it when an approved change materially affects the application's functionality, technology stack, dependencies, architecture, database, directory structure, setup instructions, or development workflow.
- Update existing sections rather than adding duplicate or contradictory information.
- Record the actual state of the project, not what the application is intended to become.
- Verify dependency versions from the relevant package manifests, lockfiles, runtime commands, or other authoritative project sources.
- Explain the purpose of important dependencies in plain language.
- Keep setup instructions consistent with the actual project scripts and configuration.
- Do not update the document for trivial changes that do not affect its contents.
- Do not create additional overview documents that duplicate `PROJECT.md` without a clear need and the user's approval.
- Never include secrets, access tokens, passwords, private keys, or sensitive user data.
- Do not place `PROJECT.md` inside `.clinefiles/`. It is maintained project documentation intended to be shared and committed to Git.

Before completing a task that materially changes the project, review whether `PROJECT.md` needs updating. Include any necessary update in the same task.

The document must remain concise, readable, and useful to a human developer. It must not become a raw dump of package manifests, generated file listings, or AI progress notes.

### User Requirements and Feature Completion

- The user's explicit requirements define the project scope. Do not independently exclude, simplify, or defer requested functionality because of perceived scope, time, or complexity.
- Implement all requested functionality to the extent possible within the existing project architecture and approved constraints.
- Do not label a requested feature as out of scope unless the user explicitly excludes it.
- If a genuine technical blocker exists, explain the specific issue, its impact, and the recommended solution. Do not silently substitute a reduced implementation.
- Avoid unrelated features, unnecessary abstractions, and unrequested enhancements.
- Use reasonable engineering judgment to resolve routine implementation details without repeatedly asking the user for decisions.
- Preserve existing functionality, authorization rules, tenant isolation, and data integrity while implementing new features.
- Report any genuinely incomplete requirements accurately, including the specific blocker and what remains to be done.

## Final Rule

When choosing between two solutions that satisfy the requirement, prefer the solution that is secure, correct, simple, readable, maintainable, and consistent with the existing codebase.

Deliver only what is needed to satisfy the approved request. Do not add extra functionality or complexity without the user's approval.
