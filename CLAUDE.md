# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Idioma

Responde siempre en español. Los términos técnicos, nombres de archivos e identificadores de código permanecen en su forma original.

## Stack

- **Backend:** Laravel 13, PHP 8.3+, Laravel Fortify (auth), Laravel Passport (API OAuth)
- **Frontend:** React 19 + TypeScript, Inertia.js (SSR disabled — CSR only), Zustand, Headless UI
- **Styling:** Tailwind CSS v4, Material Symbols icons
- **Build:** Vite 8 (`laravel-vite-plugin`)
- **Testing:** Pest 4 (not PHPUnit), SQLite in-memory
- **Linting:** Laravel Pint (preset: laravel), TypeScript strict mode

## Commands

```bash
# Start all dev servers concurrently (PHP + queue + Vite)
composer dev

# Run full test suite (config:clear → pint check → pest)
composer test

# PHP linting
composer lint          # auto-fix
composer lint:check    # check only (no changes)

# Single test
./vendor/bin/pest --filter=TestName

# Frontend only
npm run dev
npm run build
```

## Architecture

### Laravel → React via Inertia

Controllers return `Inertia::render('PageName', [...props])` instead of Blade views. Pages live in `resources/js/pages/` and are resolved automatically by `app.tsx`.

The `@/` alias maps to `resources/js/`. Page props are typed in `resources/js/types/SharedProps.ts` — every controller's props interface extends `SharedPageProps`.

### State Management

| Concern | Solution |
|---|---|
| UI ephemeral state (drawer open, toast queue) | Zustand stores in `resources/js/stores/` |
| Server / persisted data | Inertia reloads (`router.reload()`, `router.visit()`) |
| Search mode toggle (local vs MAL) | `useSearchStore` (Zustand) |

### Key Models

- **`MediaEntry`** — the core model. Has `MediaType` (anime/manga/manhwa/manhua/novel) and `MediaStatus` (watching/reading/completed/on_hold/dropped/plan_to_watch) backed-enum casts. Notable scopes: `forUser`, `search`, `ofType`, `withStatus`.
- **`User`** — standard Laravel auth user, has many `mediaEntries`.

### External API — Jikan (MyAnimeList)

`App\Services\JikanService` wraps `api.jikan.moe/v4`. Results are cached (5 min for searches, 1 hour for single lookups). Always call through this service — never call Jikan directly from controllers.

### Routes

- `routes/web.php` — authenticated Inertia routes (dashboard, search, my-list CRUD, profile)
- `routes/settings.php` — settings pages (profile, appearance, security) via Fortify
- `routes/api.php` — `api/v1/` prefix, `auth:api` (Passport), external consumer API

### Layout

`AppLayout.tsx` wraps all authenticated pages. Provides fixed desktop sidebar (`lg:w-72`), animated mobile drawer (controlled by `useUIStore`), mobile topbar, and the global toast overlay (driven by `useToastStore`).

## Design System

See `references_view/DESIGN.md` for the full spec. Key rules:

- **No borders** — separate sections with tonal shifts (`surface-container-low` vs `surface`) and negative space, never `border-*` classes.
- **Dark obsidian palette** — base background `#0e0e0e`, primary accent `#ba9eff` (electric violet).
- **xl (3rem) border radius everywhere** — never `rounded-none` or `rounded-sm`.
- **Fonts** — Manrope for display/headlines, Inter for body/labels.
- Avoid pure white (`#FFFFFF`) text; use `on-surface-variant` (`#adaaaa`) for body text.

## Migration Context

The `react` branch is an active migration from Livewire → React + Inertia (see `TODO.md` for phases). The Livewire stack (`app/Livewire/`, Flux UI Blade components) coexists temporarily. New features go in React/Inertia; do not extend the Livewire layer.

## Flux UI Credentials

`livewire/flux` requires `composer.fluxui.dev` credentials. CI uses `FLUX_USERNAME` and `FLUX_LICENSE_KEY` secrets.
