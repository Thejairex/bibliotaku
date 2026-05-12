# Plan de Migración: Livewire → React + Inertia

## Decisiones del Proyecto

| Decisión | Selección |
|----------|-----------|
| Inertia | **Standalone** (manual, sin Jetstream) |
| API | Solo para consumo externo (terceros), frontend usa Controllers |
| Estado global | **Zustand** (UI efímera) + **Inertia reloads** (datos persistidos) |
| Tests | Tests **nuevos** en Vitest/React Testing Library |
| Sidebar | Fijo en desktop + **drawer animado en mobile** |
| SSR | **CSR only** (no SSR) |

---

## Estructura de Archivos Final

```
resources/js/
├── app.tsx                           # Inertia root
├── lib/
│   └── utils.ts                      # cn() helper
├── pages/
│   ├── Home.tsx                      # Landing page
│   ├── Dashboard.tsx                 # Dashboard principal
│   ├── Search.tsx                   # Búsqueda (local + MAL)
│   ├── MyList/
│   │   ├── Index.tsx                # Lista del usuario
│   │   └── [id].tsx                 # Detalle de entrada
│   ├── Settings/
│   │   ├── Profile.tsx
│   │   ├── Appearance.tsx
│   │   └── Security.tsx
│   └── Auth/
│       ├── Login.tsx
│       ├── Register.tsx
│       ├── ForgotPassword.tsx
│       ├── ResetPassword.tsx
│       ├── TwoFactorChallenge.tsx
│       └── ConfirmPassword.tsx
├── layouts/
│   └── AppLayout.tsx                # Layout con sidebar + main
├── components/
│   ├── layout/
│   │   ├── Sidebar.tsx              # Sidebar fijo desktop
│   │   ├── MobileDrawer.tsx         # Drawer animado mobile
│   │   └── UserMenu.tsx             # Dropdown usuario
│   ├── media/
│   │   ├── MediaCard.tsx            # Tarjeta individual
│   │   ├── MediaGrid.tsx            # Grid de tarjetas
│   │   └── MALSearchModal.tsx       # Búsqueda MAL
│   ├── search/
│   │   ├── SearchBar.tsx            # Input con debounce
│   │   └── SearchModeToggle.tsx      # Switch local/MAL
│   └── ui/
│       ├── Button.tsx
│       ├── Modal.tsx
│       └── Toast.tsx
├── stores/
│   ├── useUIStore.ts                 # UI efímera
│   ├── useSearchStore.ts             # Estado búsqueda
│   └── useToastStore.ts              # Cola notificaciones
├── types/
│   ├── MediaEntry.ts               # Interfaz MediaEntry
│   ├── JikanData.ts                # Interfaz Jikan API
│   ├── User.ts                     # Interfaz User
│   ├── SharedProps.ts              # Props compartidas
│   └── Props.ts                    # Props base Inertia
└── services/
    └── api.ts                       # Cliente API
```

---

## Fases de Implementación

### Fase 1: Setup y Base ✅ COMPLETADA
| # | Tarea | Estado |
|---|-------|--------|
| 1 | Instalar deps npm | ✅ |
| 2 | Configurar Vite para React/TS | ✅ |
| 3 | Crear `app.tsx` + limpiar head | ✅ |
| 4 | Instalar `inertiajs/inertia-laravel` | ✅ |
| 5 | Configurar `HandleInertiaRequests` | ✅ |
| 6 | Crear Zustand stores | ✅ |
| 7 | Crear tipos TypeScript | ✅ |
| 8 | Crear UI base (`Button`) | ✅ |
| 9 | Verificar build | ✅ |

---

### Fase 2: Layout y Navegación
**Depende de:** Fase 1

| # | Tarea | Detalle |
|---|-------|---------|
| 1 | `AppLayout.tsx` | Layout con `<div class="flex">` sidebar + main |
| 2 | `Sidebar.tsx` | Navegación fija: Dashboard, Search, My List, Settings, Logout |
| 3 | `MobileDrawer.tsx` | Drawer animado desde izquierda con overlay. Controlado por `useUIStore` |
| 4 | `Topbar.tsx` | Header mobile: logo + hamburger que abre el drawer |
| 5 | `UserMenu.tsx` | Dropdown del usuario con avatar |
| 6 | Rutas Inertia | Configurar `web.php` para `Route::inertia()` |
| 7 | Limpiar Blade | Eliminar `livewire/`, `components/`, mantener solo layouts |

---

### Fase 3: Autenticación
**Depende de:** Fase 2 (AuthLayout)

| # | Tarea | Detalle |
|---|-------|---------|
| 1 | `Login.tsx` | Formulario login con Fortify |
| 2 | `Register.tsx` | Registro de usuario |
| 3 | `ForgotPassword.tsx` | Olvidé mi contraseña |
| 4 | `ResetPassword.tsx` | Resetear contraseña |
| 5 | `TwoFactorChallenge.tsx` | Challenge 2FA |
| 6 | `ConfirmPassword.tsx` | Confirmar contraseña |
| 7 | Tests | Flujo de auth completo |

---

### Fase 4: Core Features
**Depende de:** Fases 2+3

| # | Tarea | Detalle |
|---|-------|---------|
| 1 | `Dashboard.tsx` | Stats del usuario |
| 2 | `Search.tsx` | Búsqueda dual (local + MAL) |
| 3 | `SearchBar.tsx` | Input con debounce |
| 4 | `SearchModeToggle.tsx` | Switch local/MAL |
| 5 | `MediaGrid.tsx` + `MediaCard.tsx` | Grid responsive |
| 6 | `MALSearchModal.tsx` | Modal búsqueda MAL |
| 7 | `MyList/Index.tsx` | Lista con filtros |
| 8 | `MyList/[id].tsx` | Detalle de entrada |
| 9 | `EditEntryModal.tsx` | Editar entrada |
| 10 | Controladores Web | Actualizar para Inertia |
| 11 | Tests | Búsqueda, filtros, CRUD |

---

### Fase 5: Settings y Limpieza Final
**Depende de:** Fase 4

| # | Tarea | Detalle |
|---|-------|---------|
| 1 | Settings pages | Profile, Appearance, Security |
| 2 | Limpiar Livewire | Eliminar `app/Livewire/` |
| 3 | Limpiar Blade | Eliminar views residuales |
| 4 | Remover deps | `composer remove livewire/*` |
| 5 | Tests finales | Cobertura settings |
| 6 | Build producción | Verificar CSS, JS |

---

## Equivalencias Livewire → React

| Livewire | React + Inertia |
|----------|-----------------|
| `#[Url(as: 'q')]` | URL params via `usePage().props` |
| `wire:model.live` | `onChange` + `useDebouncedCallback` |
| `wire:click` | `onClick` + handlers |
| `wire:loading` | Estado `loading` en `useState` |
| `dispatch('entry-saved')` | `router.reload()` + Zustand toast |
| `#[On('event')]` | `useEffect` + Zustand store events |
| `return view()` | `return <MyPage />` + page props |
| `Auth::user()` | `usePage().props.auth.user` |
| `$this->resetPage()` | `router.get(url, { preserveState: true })` |

---

## Match Controladores Livewire → Web Controllers

| Livewire Component | Controller |
|--------------------|------------|
| `SearchPage` | `SearchController` |
| `MediaList` | `MediaEntryController@index` |
| `MediaSearchMal` | `MediaEntryController@store` |
| `GlobalSearch` | Integrar en `Search.tsx` |
| `Settings/Profile` | `UserProfileController@update` |
| `Settings/Appearance` | `ProfileController` |
| `Settings/Security` | `PasswordController` |

---

## Dependencias Instaladas

```json
// npm
"@inertiajs/inertia": "^0.11.1"
"@inertiajs/react": "^3.1.1"
"@vitejs/plugin-react": "^6.0.1"
"clsx": "^2.1.1"
"react": "^19.2.6"
"react-dom": "^19.2.6"
"tailwind-merge": "^3.6.0"
"zustand": "^5.0.13"
"@headlessui/react": "^2.2.10"
"typescript": "^6.0.3"
"@types/react": "^19.2.14"
"@types/react-dom": "^19.2.3"

// composer
"inertiajs/inertia-laravel": "^3.1.0"
```
