# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Idioma

Responde siempre al usuario en español.

## Stack

- **PHP ^8.3**, Laravel 13, Livewire 4, Flux UI 2
- **Node 22**, Vite 8, Tailwind 4
- **Auth**: Laravel Fortify (web) + Laravel Passport (API/OAuth)
- **Testing**: Pest PHP (not PHPUnit), SQLite in-memory
- **Linting**: Laravel Pint

## Commands

```bash
composer setup          # first-time: install, .env, migrate, npm build
composer dev            # concurrent: artisan serve + queue:listen + vite dev
composer test           # config:clear → pint --test → pest
composer lint           # pint --parallel (auto-fix)
composer lint:check     # pint --parallel --test (CI-safe, no changes)
./vendor/bin/pest --filter=TestName   # single test
```

## Architecture

### Core Domain

The app is a personal media tracker for anime, manga, manhwa, manhua, and novel. The central model is `MediaEntry` — a user-owned record representing a title in their list. Types are `MediaType` enum (`anime|manga|manhwa|manhua|novel`); statuses are `MediaStatus` enum (`watching|reading|completed|on_hold|dropped|plan_to_watch`).

Progress tracking diverges by type: anime uses `current_episode`/`total_episodes`; everything else uses `current_chapter`/`total_chapters` + `current_volume`/`total_volumes`.

### External Data: Jikan API

`app/Services/JikanService.php` wraps the Jikan v4 API (unofficial MAL API). Results are cached: 5 min for searches, 1 hour for detail lookups. The `normalize()` method maps Jikan's shape to the internal field set. Entries can be added via MAL search or manually.

### Livewire Components

`app/Livewire/` contains the interactive components:
- `MediaList` — paginated list with URL-bound filters (`status`, `type`, `q`)
- `MediaSearchMal` — live search against Jikan for adding new entries
- `SearchPage` — combined search experience
- `GlobalSearch` — site-wide search

Livewire components communicate via `entry-saved` events to refresh lists after add/edit.

### Web vs. API Routes

- **Web** (`routes/web.php`): auth-gated, Blade/Livewire, resource route at `/my-list`
- **API** (`routes/api.php`): `auth:api` (Passport), versioned under `/api/v1/`, CRUD at `/api/v1/media-entries`

### Flux UI & Credentials

Flux UI requires credentials for `composer.fluxui.dev`. In CI these come from secrets `FLUX_USERNAME` and `FLUX_LICENSE_KEY`. Locally, configure them in your Composer auth or `.env`.

## Design System

See [`references_view/DESIGN.md`](references_view/DESIGN.md) for the full spec. Key rules to enforce in all UI work:

- **No borders rule**: never use `1px solid` borders to separate sections — use tonal background shifts or negative space instead.
- **Ghost border fallback**: if a dark image bleeds into background, use `1px solid outline-variant` at **15% opacity** only.
- **Surface palette** (dark/obsidian): base `#0e0e0e` → container `#1a1919` → container-highest `#262626`.
- **Primary accent**: `#ba9eff` (violet); secondary: `#9093ff`. Never use pure white for body text.
- **Typography**: Manrope for display/headlines, Inter for body/labels.
- **Corners**: always `xl` (3rem) for large containers; never `none` or `sm`.
- **Glass nav**: `surface-container` at 70% opacity + `24px` backdrop-blur.

## Testing

Tests live in `tests/Feature/` and `tests/Unit/`. The base test case is `tests/TestCase.php`. The database uses SQLite in-memory (configured in `phpunit.xml`) — no separate test database setup needed.
