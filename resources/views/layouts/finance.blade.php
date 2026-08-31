<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'KeuKita — Finance Management untuk UMKM')</title>
  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
  {{-- Tailwind CDN (ready to migrate to Vite: resources/css/app.css) --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <script>
    // Theme init before paint — respects localStorage then system (§47)
    (function(){
      try {
        const saved = localStorage.getItem('keukita_theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const shouldDark = saved ? saved === 'dark' : prefersDark;
        if(shouldDark) document.documentElement.classList.add('dark');
      } catch(e){}
    })();
  </script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: { sans: ['Inter','system-ui','sans-serif'], display: ['Plus Jakarta Sans','Inter','sans-serif'] },
          colors: {
            brand: { 50:'#f0fdfa',100:'#ccfbf1',500:'#14b8a6',600:'#0d9488',700:'#0f766e',800:'#115e59',900:'#134e4a' },
            ink: '#0f172a',
            muted: '#64748b'
          },
          boxShadow: {
            card: '0 1px 3px rgba(15,23,42,0.06), 0 4px 12px rgba(15,23,42,0.04)',
            float: '0 8px 24px rgba(15,23,42,0.12)'
          }
        }
      }
    }
  </script>
  <style>
    *{scrollbar-width:thin;scrollbar-color:#cbd5e1 transparent}
    ::-webkit-scrollbar{width:6px;height:6px}::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:999px}
    .hide-scrollbar::-webkit-scrollbar{display:none}
    html.dark{color-scheme:dark}
    html.dark body{background:#020617;color:#e2e8f0}
    html.dark ::-webkit-scrollbar-thumb{background:#334155}
    html.dark *{scrollbar-color:#334155 transparent}
    html.dark aside{background:#0f172a !important;border-color:#1e293b !important}
    html.dark aside .border-slate-100{border-color:#1e293b !important}
    html.dark aside .border-slate-200{border-color:#1e293b !important}
    html.dark aside .bg-slate-50{background:#1e293b !important;border-color:#334155 !important}
    html.dark aside .bg-white{background:#1e293b !important}
    html.dark aside .text-slate-500{color:#94a3b8 !important}
    html.dark aside .text-slate-400{color:#64748b !important}
    html.dark aside .text-slate-700{color:#cbd5e1 !important}
    html.dark aside .hover\:bg-slate-50:hover{background:#1e293b !important}
    html.dark header{background:#0f172a !important;border-color:#1e293b !important}
    html.dark header .bg-slate-50{background:#1e293b !important;border-color:#334155 !important}
    html.dark header .bg-white{background:#1e293b !important;border-color:#334155 !important}
    html.dark header .border-slate-200{border-color:#334155 !important}
    html.dark .bg-white{background:#0f172a !important}
    html.dark .bg-slate-50{background:#1e293b !important}
    html.dark .bg-slate-900{background:#020617 !important}
    html.dark .bg-slate-100{background:#1e293b !important}
    html.dark .border-slate-100{border-color:#1e293b !important}
    html.dark .border-slate-200{border-color:#1e293b !important}
    html.dark .text-slate-900{color:#f1f5f9 !important}
    html.dark .text-slate-700{color:#cbd5e1 !important}
    html.dark .text-slate-600{color:#94a3b8 !important}
    html.dark .text-slate-500{color:#94a3b8 !important}
    html.dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]){border-color:#1e293b !important}
    html.dark thead.bg-slate-50{background:#1e293b !important}
    html.dark input, html.dark select, html.dark textarea{background:#1e293b !important;border-color:#334155 !important;color:#f1f5f9 !important}
    html.dark input::placeholder{color:#64748b !important}
    html.dark #modalTx > div:last-child > div,
    html.dark #modalAccount > div:last-child > div,
    html.dark #modalAsset > div:last-child > div,
    html.dark #modalCategory > div:last-child > div,
    html.dark #modalConfirm > div:last-child > div{background:#0f172a !important;border-color:#1e293b !important}
    html.dark #toast > div{background:#f1f5f9 !important;color:#0f172a !important}
    html.dark .bg-emerald-50{background:rgba(16,185,129,0.12) !important}
    html.dark .bg-rose-50{background:rgba(244,63,94,0.12) !important}
    html.dark .bg-sky-50{background:rgba(14,165,233,0.12) !important}
    html.dark .bg-amber-50{background:rgba(245,158,11,0.12) !important}
    html.dark .bg-indigo-50{background:rgba(99,102,241,0.12) !important}
    body, aside, header, .bg-white, .bg-slate-50{transition:background-color 0.2s, border-color 0.2s, color 0.2s}
  </style>
  @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-slate-900 font-sans antialiased dark:bg-slate-950 dark:text-slate-100">
  {{-- Mobile overlay --}}
  <div id="overlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-30 hidden lg:hidden"></div>

  {{-- SIDEBAR --}}
  <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-[280px] bg-white border-r border-slate-200 flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <div class="h-[64px] px-5 flex items-center gap-3 border-b border-slate-100 shrink-0">
      <div class="w-9 h-9 rounded-xl bg-brand-700 flex items-center justify-center text-white font-bold text-[16px]">K</div>
      <div class="flex-1 min-w-0">
        <div class="font-display font-bold text-[15px] leading-none tracking-tight">KeuKita</div>
        <div class="text-[11px] text-slate-500 tracking-wide uppercase font-medium">Finance Management</div>
      </div>
      <span class="hidden lg:inline-flex text-[10px] font-semibold tracking-widest bg-amber-100 text-amber-700 px-2 py-1 rounded-full">MVP</span>
      <button id="btnCloseSidebar" class="lg:hidden w-8 h-8 grid place-items-center rounded-lg hover:bg-slate-100">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <div class="p-3">
      <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5">
        <img id="bizLogo" src="https://api.dicebear.com/7.x/shapes/svg?seed=kopi" class="w-8 h-8 rounded-lg bg-white object-cover border border-slate-200" alt="logo"/>
        <div class="flex-1 min-w-0">
          <div id="bizName" class="text-[13px] font-semibold leading-none truncate">{{ $business->name ?? 'Kopi Sore • Coffee Shop' }}</div>
          <div id="bizType" class="text-[11px] text-slate-500 truncate">{{ $business->type ?? 'Cash • Bank • QRIS' }}</div>
        </div>
        <a href="{{ route('setup') }}" class="w-7 h-7 grid place-items-center rounded-lg hover:bg-white border border-transparent hover:border-slate-200 shrink-0" title="Setup Wizard">
          <i data-lucide="settings-2" class="w-4 h-4 text-slate-600"></i>
        </a>
      </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-5">
      <div>
        <div class="px-2 mb-2 text-[10px] font-semibold tracking-[0.14em] text-slate-400 uppercase">Menu Utama</div>
        <ul class="space-y-1">
          <li><button data-view="dashboard" class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-brand-700 text-white font-medium text-[13px]">
            <i data-lucide="layout-dashboard" class="w-[18px] h-[18px]"></i> Dashboard <span class="ml-auto w-2 h-2 bg-white rounded-full"></span>
          </button></li>
        </ul>
      </div>
      <div>
        <div class="px-2 mb-2 text-[10px] font-semibold tracking-[0.14em] text-slate-400 uppercase">Transaksi</div>
        <ul class="space-y-1">
          <li><button data-view="income" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 grid place-items-center"><i data-lucide="arrow-down-left" class="w-4 h-4"></i></span> Uang Masuk
            <span class="ml-auto text-[11px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-semibold" id="countIncome">0</span>
          </button></li>
          <li><button data-view="expense" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 grid place-items-center"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></span> Uang Keluar
            <span class="ml-auto text-[11px] bg-rose-50 text-rose-700 px-2 py-0.5 rounded-full font-semibold" id="countExpense">0</span>
          </button></li>
          <li><button data-view="transfer" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <span class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 grid place-items-center"><i data-lucide="arrow-left-right" class="w-4 h-4"></i></span> Transfer
            <span class="ml-auto text-[11px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold" id="countTransfer">0</span>
          </button></li>
        </ul>
      </div>
      <div>
        <div class="px-2 mb-2 text-[10px] font-semibold tracking-[0.14em] text-slate-400 uppercase">Keuangan</div>
        <ul class="space-y-1">
          <li><button data-view="accounts" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <i data-lucide="wallet" class="w-[18px] h-[18px] text-slate-500"></i> Accounts
            <span class="ml-auto text-xs text-slate-400" id="countAccounts">3</span>
          </button></li>
          <li><button data-view="assets" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <i data-lucide="package" class="w-[18px] h-[18px] text-slate-500"></i> Assets
            <span class="ml-auto text-xs text-slate-400" id="countAssets">0</span>
          </button></li>
          <li id="navReceivable" class="hidden"><button data-view="receivable" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <i data-lucide="hand-coins" class="w-[18px] h-[18px] text-slate-500"></i> Piutang <span class="ml-auto text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-bold">V1.5</span>
          </button></li>
          <li id="navPayable" class="hidden"><button data-view="payable" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <i data-lucide="receipt" class="w-[18px] h-[18px] text-slate-500"></i> Hutang <span class="ml-auto text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-bold">V1.5</span>
          </button></li>
        </ul>
      </div>
      <div>
        <div class="px-2 mb-2 text-[10px] font-semibold tracking-[0.14em] text-slate-400 uppercase">Laporan</div>
        <ul class="space-y-1">
          <li><button data-view="reports" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <i data-lucide="bar-chart-3" class="w-[18px] h-[18px] text-slate-500"></i> Reports
          </button></li>
        </ul>
      </div>
      <div>
        <div class="px-2 mb-2 text-[10px] font-semibold tracking-[0.14em] text-slate-400 uppercase">Pengaturan</div>
        <ul class="space-y-1">
          <li><button data-view="settings" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <i data-lucide="sliders-horizontal" class="w-[18px] h-[18px] text-slate-500"></i> Settings
          </button></li>
          <li><button data-view="audit" class="nav-btn w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 text-[13px] font-medium text-slate-700">
            <i data-lucide="shield-check" class="w-[18px] h-[18px] text-slate-500"></i> Audit Trail
          </button></li>
        </ul>
      </div>
    </nav>

    <div class="p-3 border-t border-slate-100 space-y-3">
      <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
        <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 grid place-items-center shrink-0">
          <i data-lucide="moon" class="w-4 h-4 text-slate-600 icon-moon"></i>
          <i data-lucide="sun" class="w-4 h-4 text-amber-500 icon-sun hidden"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-xs font-semibold leading-none">Tampilan</div>
          <div class="text-[11px] text-slate-500 theme-label">Mode Terang</div>
        </div>
        <button id="btnThemeToggleSidebar" class="relative w-11 h-6 rounded-full bg-slate-200 transition-colors shrink-0" aria-label="Toggle theme">
          <span class="theme-knob absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow flex items-center justify-center transition-transform">
            <i data-lucide="moon" class="w-3 h-3 text-slate-600 icon-moon"></i>
            <i data-lucide="sun" class="w-3 h-3 text-amber-500 icon-sun hidden"></i>
          </span>
        </button>
      </div>
      <div class="flex items-center gap-3">
        <img src="https://api.dicebear.com/7.x/initials/svg?seed=Owner" class="w-8 h-8 rounded-full border border-slate-200" alt="user"/>
        <div class="flex-1 min-w-0">
          <div class="text-[13px] font-semibold leading-none">Owner</div>
          <div class="text-[11px] text-slate-500">owner@keukita.id • OWNER</div>
        </div>
        <button id="btnLogout" class="w-8 h-8 grid place-items-center rounded-lg hover:bg-slate-100 text-slate-500"><i data-lucide="log-out" class="w-4 h-4"></i></button>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <button id="btnResetDemo" class="text-[11px] font-semibold py-2 rounded-xl border border-slate-200 hover:bg-slate-50">Reset Demo</button>
        <a href="{{ route('setup') }}" class="text-[11px] font-semibold py-2 rounded-xl bg-slate-900 text-white grid place-items-center hover:bg-black">Setup Ulang</a>
      </div>
    </div>
  </aside>

  <div class="lg:pl-[280px] min-h-screen flex flex-col">
    <header class="h-[64px] bg-white border-b border-slate-200 sticky top-0 z-20 flex items-center gap-3 px-4 lg:px-6">
      <button id="btnOpenSidebar" class="lg:hidden w-9 h-9 grid place-items-center rounded-xl border border-slate-200">
        <i data-lucide="menu" class="w-5 h-5"></i>
      </button>
      <div class="hidden md:flex items-center gap-2 text-[13px]">
        <span class="text-slate-400">Bisnis</span><span class="text-slate-300">/</span><span id="breadcrumb" class="font-semibold">@yield('breadcrumb', 'Dashboard')</span>
      </div>
      <div class="flex-1"></div>
      <div class="hidden md:flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 w-[280px]">
        <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
        <input id="globalSearch" placeholder="Cari transaksi, kategori..." class="bg-transparent outline-none text-[13px] w-full placeholder:text-slate-400" />
        <span class="text-[10px] bg-white border border-slate-200 px-1.5 py-0.5 rounded font-medium text-slate-500">/</span>
      </div>
      <div class="hidden md:flex items-center gap-2">
        <button data-quick="income" class="h-9 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[13px] font-semibold inline-flex items-center gap-1.5">
          <i data-lucide="plus" class="w-4 h-4"></i> Uang Masuk
        </button>
        <button data-quick="expense" class="h-9 px-3 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-[13px] font-semibold inline-flex items-center gap-1.5">
          <i data-lucide="minus" class="w-4 h-4"></i> Uang Keluar
        </button>
        <button data-quick="transfer" class="h-9 w-9 grid place-items-center rounded-xl bg-white border border-slate-200 hover:bg-slate-50">
          <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
        </button>
      </div>
      <button id="btnThemeToggle" class="w-9 h-9 grid place-items-center rounded-xl bg-white border border-slate-200 hover:bg-slate-50 transition-colors" title="Toggle dark mode">
        <i data-lucide="moon" class="w-4 h-4 icon-moon"></i>
        <i data-lucide="sun" class="w-4 h-4 icon-sun hidden"></i>
      </button>
      <button class="w-9 h-9 grid place-items-center rounded-xl bg-white border border-slate-200 relative">
        <i data-lucide="bell" class="w-4 h-4"></i>
        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
      </button>
    </header>

    <main class="flex-1 p-4 lg:p-6 max-w-[1280px] w-full mx-auto">
      @yield('content')
    </main>

    <div class="lg:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 p-3 flex gap-2 z-20">
      <button data-quick="income" class="flex-1 py-3 rounded-xl bg-emerald-600 text-white text-sm font-bold">+ Masuk</button>
      <button data-quick="expense" class="flex-1 py-3 rounded-xl bg-white border border-slate-200 text-sm font-bold">− Keluar</button>
      <button data-quick="transfer" class="w-14 grid place-items-center rounded-xl bg-slate-900 text-white"><i data-lucide="arrow-left-right" class="w-5 h-5"></i></button>
    </div>
  </div>

  {{-- Global Toast --}}
  <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden">
    <div class="bg-slate-900 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-float flex items-center gap-3">
      <span class="w-7 h-7 rounded-lg bg-white/15 grid place-items-center"><i data-lucide="check" class="w-4 h-4"></i></span>
      <span id="toastMsg">Berhasil disimpan</span>
    </div>
  </div>

  <script>lucide.createIcons();</script>
  @stack('scripts')
</body>
</html>
