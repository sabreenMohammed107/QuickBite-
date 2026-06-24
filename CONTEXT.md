# QuickBite — System Architecture & Context Log

> **Reference document for all developers.** Updated after every major implementation phase.
> Last updated: 2026-06-24

---

## Global System Strategy

| Property | Value |
|---|---|
| Platform | Scalable, Multi-Region Food Delivery & Ordering |
| Parties | Customers, Restaurant Owners/Staff, Delivery Agents, Admins |
| Pattern | Domain-Driven Design (DDD) — Actions over Controllers |
| Write Rule | All write operations wrapped in `DB::transaction()` |
| Dev Environment | XAMPP on Windows, PHP 8.3/8.4, Laravel 13 |
| Auth (API) | Laravel Sanctum — token-based |
| Auth (Admin) | Laravel Session — web guard |

---

## Multi-Database Architecture

| # | Service | Connection | Purpose |
|---|---|---|---|
| 1 | **Core Service** (current scope) | `mysql_core` | Users, restaurants, branches, menus, roles |
| 2 | **Orders & Payments** | `mysql_orders` | High-throughput order transactions, ledger |
| 3 | **Analytics** | MongoDB | Clickstreams, logs, reporting |

### Connection Config (`config/database.php`)
- Default connection: `mysql_core`
- `mysql_core` uses env prefix `DB_CORE_*`
- `mysql_orders` uses env prefix `DB_ORDERS_*`
- Every Core Service Eloquent model explicitly declares `$connection = 'mysql_core'`

### `.env` Keys
```
DB_CORE_HOST=127.0.0.1
DB_CORE_PORT=3306
DB_CORE_DATABASE=quickbite_core
DB_CORE_USERNAME=root
DB_CORE_PASSWORD=

DB_ORDERS_HOST=127.0.0.1
DB_ORDERS_PORT=3306
DB_ORDERS_DATABASE=quickbite_orders
DB_ORDERS_USERNAME=root
DB_ORDERS_PASSWORD=
```

---

## Core Service — Database Schema

### `users`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `name` | varchar | |
| `email` | varchar UNIQUE | |
| `password` | varchar | bcrypt |
| `role` | enum | `customer`, `restaurant_owner`, `delivery_agent`, `admin` |
| `status` | enum | `active`, `inactive`, `suspended` |
| `remember_token` | varchar | session persistence |
| `timestamps` | | |

### `restaurants`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `name` | varchar | |
| `status` | varchar | |
| `logo_url` | varchar nullable | |
| `primary_country` | varchar | stored uppercase |
| `timestamps` | | |

### `restaurant_branches`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `restaurant_id` | FK → restaurants | |
| `country_code` | varchar | |
| `address_text` | varchar | |
| `label` | varchar | e.g. "Main Branch" |
| `lat` | decimal(10,8) | |
| `lng` | decimal(11,8) | |
| `is_active` | boolean | |
| `opens_at` | time | |
| `closes_at` | time | |
| `accept_orders` | boolean | |
| `delivery_radius` | smallint | km |
| `timestamps` | | |

Index: composite `['lat', 'lng']` for Haversine queries.

### `products`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `restaurant_id` | FK → restaurants | |
| `name` | varchar | |
| `description` | text nullable | |
| `image_url` | varchar nullable | |
| `timestamps` | | |

### `product_branch_details`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `branch_id` | FK → restaurant_branches | |
| `product_id` | FK → products | |
| `price` | decimal(10,2) | currency precision |
| `stock` | integer | |
| `is_available` | boolean | |
| `timestamps` | | |

Constraint: unique composite `['branch_id', 'product_id']`.

### `restaurant_members`
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `user_id` | FK → users | |
| `restaurant_id` | FK → restaurants | |
| `role` | enum | `owner`, `manager`, `cashier` |
| `status` | varchar | |
| `timestamps` | | |

### `personal_access_tokens`
Standard Sanctum table — pinned to `mysql_core` via custom migration override.

---

## PHP Enums

```
app/Domains/Auth/Enums/
  UserRole.php         → Customer, RestaurantOwner, DeliveryAgent, Admin
  UserStatus.php       → Active, Inactive, Suspended

app/Domains/Restaurant/Enums/
  MemberRole.php       → Owner, Manager, Cashier
```

All enums are PHP 8.1+ **backed string enums**. Cast directly in Eloquent models via the `$casts` array.

---

## Eloquent Models

All models pin `$connection = 'mysql_core'`.

| Model | File | Key Relationships |
|---|---|---|
| `User` | `app/Models/User.php` | `HasApiTokens`, `restaurantMembers()` HasMany |
| `Restaurant` | `app/Models/Restaurant.php` | `branches()`, `products()`, `members()` |
| `RestaurantBranch` | `app/Models/RestaurantBranch.php` | `restaurant()`, `productDetails()` |
| `Product` | `app/Models/Product.php` | `restaurant()`, `branchDetails()` |
| `ProductBranchDetail` | `app/Models/ProductBranchDetail.php` | `branch()`, `product()` |
| `RestaurantMember` | `app/Models/RestaurantMember.php` | `user()`, `restaurant()` |

---

## Actual Directory Structure (as built)

```
app/
├── Domains/
│   ├── Auth/
│   │   ├── Actions/
│   │   │   ├── RegisterUserAction.php          ← DB::transaction, sets UserStatus::Active
│   │   │   └── LoginUserAction.php             ← Auth::attempt(), returns ?User
│   │   └── Enums/
│   │       ├── UserRole.php
│   │       └── UserStatus.php
│   └── Restaurant/
│       ├── Actions/
│       │   ├── CreateBranchAction.php                  ← transaction wrapping
│       │   └── ToggleProductAvailabilityAction.php     ← flips is_available in transaction
│       ├── Enums/
│       │   └── MemberRole.php
│       └── Services/
│           └── GeospatialLocationService.php           ← Haversine via MySQL raw query
│
├── Http/
│   └── Controllers/
│       ├── Admin/
│       │   ├── Auth/
│       │   │   └── LoginController.php     ← web session login, role-gated to Admin
│       │   ├── RestaurantController.php    ← CRUD (no show)
│       │   ├── RestaurantBranchController.php
│       │   ├── ProductController.php
│       │   └── ProductBranchDetailController.php
│       └── Api/
│           └── V1/
│               ├── Auth/
│               │   ├── RegisterController.php
│               │   └── LoginController.php
│               └── Restaurant/
│                   └── NearbyBranchController.php
│
└── Models/
    ├── User.php
    ├── Restaurant.php
    ├── RestaurantBranch.php
    ├── Product.php
    ├── ProductBranchDetail.php
    └── RestaurantMember.php

database/
└── seeders/
    ├── DatabaseSeeder.php      ← calls AdminSeeder
    └── AdminSeeder.php         ← creates super admin via updateOrCreate

resources/
├── css/app.css                 ← @import 'tailwindcss'; + @source blade views
├── js/app.js
└── views/
    ├── layouts/
    │   └── dashboard.blade.php ← master admin layout (sidebar + navbar)
    ├── auth/
    │   └── login.blade.php     ← standalone admin login page
    └── admin/
        ├── restaurants/        index · create · edit
        ├── branches/           index · create · edit
        ├── products/           index · create · edit
        └── product-details/    index · create · edit

routes/
├── web.php                     ← login, logout, admin resource routes
└── api.php                     ← /api/v1 prefix, Sanctum-guarded routes
```

---

## GeospatialLocationService

**File:** `app/Domains/Restaurant/Services/GeospatialLocationService.php`

Uses a raw MySQL Haversine query with a `GREATEST(-1, LEAST(1, ...))` domain clamp around the `ACOS()` call. This prevents floating-point imprecision from pushing the ACOS argument outside [-1, 1] and causing SQL errors.

```php
GeospatialLocationService::findNearbyBranches(float $lat, float $lng, int $radiusKm): Collection
// Returns active branches within $radiusKm, ordered by distance ASC
```

---

## Routing Reference

### Web (`routes/web.php`)

| Method | URI | Middleware | Name / Action |
|---|---|---|---|
| GET | `/` | — | → `admin.restaurants.index` |
| GET | `/login` | `guest` | `login` — show form |
| POST | `/login` | `guest` | handle login |
| POST | `/logout` | `auth` | `logout` — clear session |
| GET | `/admin/` | `auth` | → `admin.restaurants.index` |
| CRUD | `/admin/restaurants` | `auth` | `admin.restaurants.*` |
| CRUD | `/admin/branches` | `auth` | `admin.branches.*` |
| CRUD | `/admin/products` | `auth` | `admin.products.*` |
| CRUD | `/admin/product-details` | `auth` | `admin.product-details.*` (param: `$detail`) |

All CRUD resources exclude `show`.

### API (`routes/api.php`) — prefix `/api/v1`

| Method | URI | Middleware | Action |
|---|---|---|---|
| POST | `/api/v1/auth/register` | — | RegisterController |
| POST | `/api/v1/auth/login` | — | LoginController |
| GET | `/api/v1/restaurants/branches/nearby` | `auth:sanctum` | NearbyBranchController |

---

## Admin Dashboard

### Layout (`resources/views/layouts/dashboard.blade.php`)
- Dark slate sidebar (`w-64`) + white main content area
- Sidebar: QuickBite logo, nav links (Restaurants, Branches, Products, Product Details), user footer showing live `auth()->user()->name` + email + logout button
- Top navbar: `@yield('title')` slot, mobile hamburger, logged-in user chip
- Tailwind via **CDN** (`@tailwindcss/browser@4`) — see Vite note below

### Admin Views (`resources/views/admin/`)
Each section has three views: `index` (table list), `create` (form), `edit` (form + delete).

### Delete Pattern
Edit views use a **hidden external `<form id="delete-*-form">`** triggered by an `onclick` confirm button. This is required because HTML forbids `<form>` elements nested inside another `<form>`.

---

## Authentication

### Admin Panel (Session / Web Guard)

| Aspect | Detail |
|---|---|
| Guard | `web` (session-based) |
| Model | `App\Models\User` on `mysql_core` |
| Login URL | `GET /login` |
| Role check | Login controller rejects users where `role !== UserRole::Admin` |
| Post-login redirect | `redirect()->intended(route('admin.restaurants.index'))` |
| Unauthenticated redirect | Auto → `route('login')` (Laravel default) |
| Logout | `Auth::logout()` + `session()->invalidate()` + `regenerateToken()` |

### API (Sanctum)

The `User` model uses `HasApiTokens`. Token routes use `middleware('auth:sanctum')`. Sanctum's `personal_access_tokens` table lives on `mysql_core`.

---

## Seeders

### AdminSeeder

**File:** `database/seeders/AdminSeeder.php`

Uses `User::updateOrCreate()` so it is always safe to re-run.

```
Email:    admin@quickbite.com
Password: Admin@1234
Role:     UserRole::Admin
Status:   UserStatus::Active
```

Run:
```bash
php artisan db:seed                          # runs DatabaseSeeder → AdminSeeder
php artisan db:seed --class=AdminSeeder      # run AdminSeeder only
```

---

## Frontend / Asset Pipeline

### Current State: CDN Mode

Node.js 20.12.2 is installed locally. Vite 8 (via laravel-vite-plugin 3.x) requires Node ≥ 20.19+, so `npm run build` currently fails.

**Workaround:** Both `dashboard.blade.php` and `auth/login.blade.php` load Tailwind from the official CDN:
```html
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<style type="text/tailwindcss">
    @theme { --font-sans: ui-sans-serif, system-ui, sans-serif; }
</style>
```

### Permanent Fix (upgrade path)

1. Upgrade Node.js to **20.19 LTS** or **22.x LTS**
2. `npm install && npm run build`
3. Replace CDN `<script>` in both layout files with:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

---

## Key Engineering Decisions

| # | Decision | Reason |
|---|---|---|
| 1 | All models pin `$connection = 'mysql_core'` | Prevents accidental cross-DB queries if the default connection ever changes |
| 2 | `GREATEST(-1, LEAST(1, ...))` in Haversine | MySQL `ACOS()` domain is [-1, 1]; float imprecision can breach it and throw a SQL error |
| 3 | `->parameters(['product-details' => 'detail'])` on resource route | Avoids illegal PHP variable name `$product-details` from kebab-case route binding |
| 4 | `$request->boolean()` for checkboxes | Correctly maps checkbox `"on"` / absent to `true` / `false` |
| 5 | `Rule::unique()->where()->ignore()` on ProductBranchDetail | Composite unique validation that exempts the current row on edit |
| 6 | Delete forms outside the edit form | HTML forbids nested `<form>` elements; DELETE action must be a separate form |
| 7 | Admin login checks `role === UserRole::Admin` | Prevents non-admin users from accessing the panel even with valid credentials |
| 8 | `updateOrCreate` in AdminSeeder | Idempotent — safe to run in CI or on fresh databases without duplicate key errors |

---

## Known Issues / Constraints

| Issue | Status | Fix |
|---|---|---|
| `npm run build` fails | Open | Upgrade Node.js to 20.19+ or 22.x |
| Tailwind via CDN only | Open | Resolved automatically when Node is upgraded |
| Password reset not wired up | Open | `password_reset_tokens` table exists; needs mail config + routes |
| Sessions stored in DB | Working | `SESSION_DRIVER=database` → `sessions` table on `mysql_core` |

---

## Quick-Start Commands

```bash
# First-time setup
php artisan migrate
php artisan db:seed

# After changing .env
php artisan config:clear
php artisan cache:clear

# Start local server
php artisan serve

# URLs
http://127.0.0.1:8000/login              ← Admin login
http://127.0.0.1:8000/admin/restaurants  ← Dashboard entry point

# Credentials
Email:    admin@quickbite.com
Password: Admin@1234
```
---

## 🔐 Unified Authentication & Routing Strategy
- **Single Login Page:** A unified login interface at `/login` for all user types.
- **Dynamic Redirection:** Upon successful authentication, a custom Middleware (`RedirectBasedOnRole`) inspects `users.role`:
  - `admin` -> Redirects to `/admin/dashboard`
  - `restaurant_owner` / `manager` / `cashier` -> Redirects to `/merchant/dashboard`
  - `customer` -> Redirects to frontend user portal `/home`.
- **Session Context:** For restaurant staff, the middleware or session must bind the active `restaurant_id` from the `restaurant_members` table to scope their dashboard views.

### 4. RBAC & Flexible Permissions Module (`restaurant_members`)
- Bridges staff management with dynamic, fine-grained application permissions.
- `id` (bigint)
- `user_id` (foreignId -> users)
- `restaurant_id` (foreignId -> restaurants)
- `role` (enum: owner, manager, cashier, staff)
- `status` (string: active, inactive)
- `permissions` (json) -> Stores granular capability arrays for the specific restaurant context (e.g., `["orders.view" , "orders.create", "menu.edit"]`).
- *Constraint:* Super Admin (`users.role = 'admin'`) bypasses all checks and inherits ALL permissions globally.

---

## 🖥️ Custom Dashboard Infrastructure (No Packages)
...
- **Super Admin Capabilities:** Full CRUD access to all resources, ability to create restaurant accounts, assign them to brands/branches, and check/uncheck modular permission checkboxes dynamically via the UI forms.

5. **Flexible RBAC Setup:** Update `restaurant_members` migration to include a `permissions` JSON column. Implement a helper method or Middleware `CheckRestaurantPermission` to protect routes based on the active restaurant context and JSON array capabilities.
