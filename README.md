# KeuKita — Finance Management UMKM (Laravel + Blade + Tailwind)

> **PRD v1.0 MVP** — Generic finance management untuk UMKM (Coffee Shop, Bakery, Travel, Retail, Services, dll).  
> **Prinsip:** *Simple on the surface, powerful underneath* — Uang Masuk / Keluar / Transfer, sistem urus profit & cashflow di belakang.

`Laravel 12 + Blade + Tailwind CDN (dark mode) + Chart.js + Lucide` — siap di-deploy per-client & kembang ke SaaS.

## Jalankan (Laravel)

```bash
# 1. Install deps (sudah ter-install via composer create-project)
# C:\xampp\php\php.exe C:\xampp\php\composer.phar install

# 2. Env & key (sudah)
copy .env.example .env
php artisan key:generate

# 3. DB (sqlite default, sudah migrate)
php artisan migrate

# 4. Serve
php artisan serve
# atau via XAMPP: C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000

# Buka:
http://localhost:8000/          # redirect → /dashboard
http://localhost:8000/dashboard # SPA Dashboard + Transactions + Reports
http://localhost:8000/setup     # Wizard 5 step (<5 menit)
```

Tanpa `npm` — Tailwind & Chart via CDN (mudah pindah ke Vite `resources/css/app.css` nanti).

## Struktur Blade

```
resources/views/layouts/app.blade.php  # Shell: <head> darkMode, sidebar (nav §10), header, <main>@yield, toast, @stack('scripts')
resources/views/dashboard.blade.php    # SPA utama — extends layouts.app
  ├─ view-dashboard: Financial Health, Cashflow Chart, Summary, Recent (§11-13, §49)
  ├─ view-income / expense / transfer  (§16-18, §31, <30 detik)
  ├─ view-accounts / assets            (§19-21)
  ├─ view-reports + filters + export   (§32-34)
  ├─ view-settings / audit             (§39, §43)
  └─ modals: transaction, account, asset, category, confirm
resources/views/setup.blade.php        # Standalone wizard §8 — 5 step, template §9, tidak pakai layout
public/assets/js/app.js                # Financial engine JS (mirror FinanceService.php)
public/assets/js/setup.js              # Wizard JS (localStorage + window.dashboardUrl)
```

**Routes `routes/web.php:15`:**
```php
/ → redirect dashboard
/dashboard → DashboardController@index (Blade SPA)
/setup GET+POST → SetupController@index/store
/api/health → json
```

**Controllers:**
- `app/Http/Controllers/DashboardController.php:9` — hydrate `$business` dari DB (saat ini null → JS localStorage)
- `app/Http/Controllers/SetupController.php:9` — validate & future persist ke `businesses` + `accounts` + `categories`

## Frontend → Blade

- **Layout** `layouts/app.blade.php:1` — `<html lang="{{ app()->getLocale() }}">`, `@yield('title')`, `@yield('breadcrumb')`, `@yield('content')`, `@stack('scripts')`, CSRF meta, `{{ asset('assets/js/app.js') }}`
- **Dark mode** `index.html:14` & `layouts/app.blade.php:12` — `localStorage keukita_theme` → `prefers-color-scheme`, toggle header `id="btnThemeToggle"` & sidebar `id="btnThemeToggleSidebar"`, `html.dark` overrides, `color-scheme:dark`, transisi 200ms, Chart grid adapt
- **Aset** — `public/assets/js/app.js` & `setup.js` di-load via `{{ asset() }}`; `setup.js:210` pakai `window.dashboardUrl = "{{ route('dashboard') }}"`

## Database (PRD §53)

Migrasi `database/migrations/2026_08_30_*:`
- `businesses` + `business_users` (§6 1 user → 1 business + admins, business_id isolation §41)
- `accounts` (Cash/Bank/E-Wallet/Other, opening_balance §20)
- `categories` (INCOME/EXPENSE, classification COGS/Operational..., affects_profit §15)
- `transactions` (INCOME/EXPENSE/TRANSFER, POSTED/VOIDED §56-57, §40 no hard delete)
- `assets` (§21, purchase_price tidak kurangi profit)
- `audit_logs` (§39)

Models `app/Models/*` — Business, Account, Category, Transaction, Asset, AuditLog + User::businesses().

Service `app/Services/Finance/FinanceService.php:12` — `accountBalance()`, `availableCash()`, `metrics()` (Gross = Revenue-COGS, Net = Gross-Opex), `void()` — mirror `app.js: accountBalance()`, `computeMetrics()`.

Enums `app/Enums/TransactionType|Status.php`

## Financial Rules (§42)

Semua 12 rules di `FinanceService.php` & `app.js` — silakan cek `app/Services/Finance/FinanceService.php:1` dan `public/assets/js/app.js:4` (fmtIDR, parseAmount, TEMPLATES §9).

## Dark Mode

Toggle di header & sidebar, persist `keukita_theme`, sync `index ↔ setup`, hormati system. Kelas `html.dark` override `bg-white`/`border-slate-200`/`text-slate-*` dll. Coba ubah OS ke dark lalu reload.

## Next → Livewire

Blade ini siap di-extract ke Livewire per PRD §51:
```
app/Livewire/Dashboard/...
app/Livewire/Transactions/...
app/Livewire/Accounts/...
resources/views/livewire/...
```
Ganti `@push('scripts')` + `localStorage` dengan `wire:model` + `FinanceService` di server, tapi class Tailwind & struktur tetap.

## Static fallback (sebelum Laravel)

File statis asli di-backup ke `C:\Users\daffa\AppData\Local\Temp\keukita-backup\` — bisa dibuka langsung `index.html` tanpa server.
