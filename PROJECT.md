# PROJECT.md

## Project Purpose
Laravel marketplace SaaS starter project (blank Vue starter kit). Currently a default Laravel boilerplate with an Inertia + Vue frontend. No marketplace business features are implemented yet.

## Current Functionality
- Single route `/` rendering an Inertia `Welcome` Vue page.
- Health check route `up`.
- Laravel Boost browser-logs endpoint and Inertia devtools endpoints (dev).
- No authentication flows, roles, tenants, or marketplace features implemented.

## Technology Stack
- PHP 8.3.23, Laravel Framework 13.38.0
- Composer 2.10.3, Node v23.5.0, npm 10.9.2
- Frontend: Vue 3.5.13, Inertia.js v3, Tailwind CSS 4.1, Vite 8, TypeScript 5.2
- Database: SQLite (default `.env`); MySQL intended but not configured
- Testing: Pest 4.7, Mockery, Faker

## Dependencies
- `inertiajs/inertia-laravel` ^3.0 — server-side Inertia
- `laravel/framework` ^13.17 — core
- `laravel/tinker` ^3.0 — REPL
- `laravel/wayfinder` ^0.1.14 — TS route/action generation
- dev: `laravel/boost` ^2.2, `laravel/pint`, `larastan`, `pestphp/pest` ^4.7, `mockery/mockery`

## Application Structure
- `app/` — Http (Controller, Middleware HandleInertiaRequests), Models (User), Providers
- `resources/js` — app.ts, pages/Welcome.vue, types, wayfinder generated files
- `routes/web.php` — home route
- `database/migrations` — users, cache, jobs
- `tests/` — Pest Feature/Unit examples

## Database Overview
- SQLite configured. Tables: users, password_reset_tokens, sessions, cache, jobs. No tenant/marketplace tables.

## Auth & Authorization
- Added multi-tenant foundation schema: marketplaces, marketplace_applications, marketplace_memberships, subscriptions; User has platform admin flag and relationships. Tests pass.
## Development Setup
- `composer install`, copy `.env`, `php artisan key:generate`, `php artisan migrate`, `npm install`, `npm run build`
- Dev: `composer dev` (artisan dev), `npm run dev`
- Tests: `php artisan test`, `composer test` (includes lint/types/test)
- Build: `npm run build`, `npm run build:ssr`; checks: `npm run check`, `npm run types:check`

## Architecture & Integrations
- Inertia v3 SPA pattern, Vite + `@inertiajs/vite`, Tailwind v4 via `@tailwindcss/vite`.
- Laravel Boost configured (boost.json agents: copilot, guidelines, mcp, skills).

## Known Limitations / Planned (not implemented)
- Multi-tenant marketplace SaaS: platform admins, marketplace owners with manual approval, sellers, buyers, optional delivery; isolated marketplaces; subscriptions; branding/settings. None implemented.
- No Git repository present.
- DB is SQLite, intended MySQL.
- Frontend uses TypeScript, earlier intended JS — decision needed.

## Gaps Summary
Auth flows, roles/permissions, tenancy, marketplace domain entities, application/approval workflow, subscriptions, and tests for these are absent.
