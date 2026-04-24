# AGENTS.md

## Stack
- Laravel 13, Livewire 4, Flux UI 2
- PHP ^8.3 (CI: 8.3–8.5), Node 22
- Pest PHP (not PHPUnit), Pint for linting
- Vite 8 + Tailwind 4

## Commands
- `composer test` — config:clear → pint --test → pest
- `composer dev` — concurrent: artisan serve, queue:listen, vite dev
- `./vendor/bin/pest --filter=TestName` — single test
- `composer lint` — pint --parallel

## Flux UI
Requires credentials for `composer.fluxui.dev`. CI uses secrets `FLUX_USERNAME` and `FLUX_LICENSE_KEY`.

## Testing
- SQLite in-memory (phpunit.xml)
- Pest tests in `tests/Feature/` and `tests/Unit/`
- Base test case: `tests/TestCase.php`

## Architecture
- `app/Livewire/` — Livewire components
- `app/Services/` — business logic
- `routes/web.php` — main routes
- `resources/css/app.css`, `resources/js/app.js` — Vite entry points

## Design
See `references_view/DESIGN.md` — "no borders" rule, obsidian palette, Manrope + Inter typography.
