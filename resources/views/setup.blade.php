<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Setup Wizard — KeuKita</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <script>
    (function(){
      try{
        const saved=localStorage.getItem('keukita_theme');
        const prefersDark=window.matchMedia('(prefers-color-scheme: dark)').matches;
        if((saved? saved==='dark': prefersDark)) document.documentElement.classList.add('dark');
      }catch(e){}
    })();
  </script>
  <script>
    tailwind.config={darkMode:'class', theme:{extend:{fontFamily:{sans:['Inter','sans-serif'],display:['Plus Jakarta Sans','Inter','sans-serif']},colors:{brand:{600:'#0d9488',700:'#0f766e'}}}}}
  </script>
  <style>
    html.dark{color-scheme:dark}
    html.dark body{background:#020617 !important;color:#e2e8f0}
    html.dark .bg-white{background:#0f172a !important}
    html.dark .bg-slate-50{background:#1e293b !important}
    html.dark .bg-slate-100{background:#1e293b !important}
    html.dark .border-slate-200{border-color:#1e293b !important}
    html.dark .border-slate-100{border-color:#1e293b !important}
    html.dark .text-slate-500{color:#94a3b8 !important}
    html.dark .text-slate-400{color:#64748b !important}
    html.dark input, html.dark select{background:#1e293b !important;border-color:#334155 !important;color:#f1f5f9 !important}
    html.dark input::placeholder{color:#64748b !important}
    body, .bg-white, .bg-slate-50{transition:background-color 0.2s, border-color 0.2s, color 0.2s}
  </style>
<script>window.dashboardUrl = "{{ route('dashboard') }}";</script>
</head>
<body class="bg-[#F8FAFC] min-h-screen font-sans antialiased dark:bg-slate-950 dark:text-slate-100">
  <div class="max-w-[960px] mx-auto px-4 py-6 lg:py-10">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-brand-700 grid place-items-center text-white font-bold">K</div>
        <div><div class="font-display font-bold leading-none">KeuKita</div><div class="text-xs text-slate-500">Business Setup Wizard • &lt;5 menit (§8)</div></div>
      </div>
      <div class="flex items-center gap-2">
        <button id="btnThemeToggle" class="w-9 h-9 grid place-items-center rounded-xl bg-white border border-slate-200 hover:bg-slate-50" title="Toggle dark mode">
          <i data-lucide="moon" class="w-4 h-4 icon-moon"></i><i data-lucide="sun" class="w-4 h-4 icon-sun hidden"></i>
        </button>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold border border-slate-200 bg-white px-3 py-2 rounded-xl hover:bg-slate-50">Lewati → Dashboard</a>
      </div>
    </div>

    <!-- Progress -->
    <div class="mt-6 bg-white rounded-2xl border border-slate-200 p-4">
      <div class="flex items-center gap-2">
        <div id="stepDots" class="flex items-center gap-2 flex-1"></div>
        <div class="text-xs font-semibold text-slate-500"><span id="stepLabel">Step 1/5</span> • <span id="stepTitle">Business Information</span></div>
      </div>
      <div class="mt-3 h-2 bg-slate-100 rounded-full overflow-hidden"><div id="stepBar" class="h-full bg-brand-700 transition-all" style="width:20%"></div></div>
    </div>

    <!-- Card -->
    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <!-- Step 1 -->
      <div id="panel-1" class="p-6 lg:p-8">
        <h2 class="font-display text-xl font-bold">Informasi Bisnis</h2>
        <p class="text-sm text-slate-500 mt-1">Langkah 1 dari 5 • Pilih template sesuai jenis usaha (§9)</p>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2"><label class="text-xs font-semibold">Nama Bisnis *</label><input id="wName" placeholder="Kopi Sore" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm font-semibold" value="Kopi Sore"/></div>
          <div><label class="text-xs font-semibold">Tipe Bisnis</label><select id="wType" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white">
            <option>Coffee Shop</option><option>Bakery / Patisserie</option><option>Restaurant</option><option>Catering</option><option>Travel</option><option>Retail</option><option>Salon / Barbershop</option><option>Laundry</option><option>Services</option><option>Online Shop</option><option>Other</option>
          </select></div>
          <div><label class="text-xs font-semibold">Currency</label><select id="wCurrency" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"><option>IDR (Rp)</option><option>USD ($)</option></select></div>
          <div><label class="text-xs font-semibold">Timezone</label><select id="wTz" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"><option>Asia/Jakarta</option><option>Asia/Makassar</option><option>Asia/Jayapura</option></select></div>
          <div><label class="text-xs font-semibold">Logo (opsional)</label><div class="mt-1 flex items-center gap-3 border border-dashed border-slate-200 rounded-xl px-3 py-3"><img id="wLogoPreview" src="https://api.dicebear.com/7.x/shapes/svg?seed=kopi" class="w-10 h-10 rounded-xl border"/><input id="wLogo" placeholder="https://..." class="flex-1 outline-none text-sm" value="https://api.dicebear.com/7.x/shapes/svg?seed=kopi"/><button id="btnPickLogo" class="text-xs font-bold border px-3 py-1.5 rounded-full">Acak</button></div></div>
        </div>
        <div class="mt-6 bg-slate-50 border border-slate-200 rounded-xl p-4">
          <div class="text-xs font-bold tracking-widest uppercase text-slate-500">Preview template</div>
          <div id="templatePreview" class="mt-2 text-sm leading-relaxed"></div>
        </div>
      </div>

      <!-- Step 2 -->
      <div id="panel-2" class="hidden p-6 lg:p-8">
        <h2 class="font-display text-xl font-bold">Accounts</h2>
        <p class="text-sm text-slate-500">Default dibuat dari template. Kamu bisa tambah/edit/archive.</p>
        <div id="wAccounts" class="mt-4 space-y-2"></div>
        <div class="mt-4 flex gap-2">
          <input id="wAccName" placeholder="Nama akun baru (mis. BCA)" class="flex-1 border border-slate-200 rounded-xl px-3 py-2.5 text-sm"/>
          <select id="wAccType" class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white"><option>Cash</option><option>Bank</option><option>E-Wallet</option></select>
          <input id="wAccOpening" placeholder="0" class="w-32 border border-slate-200 rounded-xl px-3 py-2.5 text-sm" inputmode="numeric"/>
          <button id="btnAddWAcc" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold">Tambah</button>
        </div>
        <p class="text-[11px] text-slate-500 mt-2">Opening balance tidak mempengaruhi profit (§20)</p>
      </div>

      <!-- Step 3 -->
      <div id="panel-3" class="hidden p-6 lg:p-8">
        <h2 class="font-display text-xl font-bold">Categories</h2>
        <p class="text-sm text-slate-500">Atur kategori income & expense. Expense bisa ditandai COGS & affects_profit.</p>
        <div class="mt-4 flex gap-2">
          <button data-wcat="income" class="wcat-tab px-3 py-1.5 rounded-full text-xs font-bold bg-slate-900 text-white">Income</button>
          <button data-wcat="expense" class="wcat-tab px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100">Expense</button>
          <span class="ml-auto text-xs text-slate-500" id="wCatCount"></span>
        </div>
        <div id="wCategories" class="mt-4 space-y-2"></div>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-2">
          <input id="wCatName" placeholder="Nama kategori" class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm"/>
          <select id="wCatType" class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white"><option value="INCOME">Income</option><option value="EXPENSE" selected>Expense</option></select>
          <select id="wCatClass" class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white"><option>Sales</option><option>COGS</option><option>Operational</option><option>Marketing</option><option>Salary</option><option>Rent</option><option>Other</option></select>
          <button id="btnAddWCat" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold">Tambah</button>
        </div>
      </div>

      <!-- Step 4 -->
      <div id="panel-4" class="hidden p-6 lg:p-8">
        <h2 class="font-display text-xl font-bold">Financial Settings</h2>
        <p class="text-sm text-slate-500">Pilih fitur yang kamu pakai. Yang off akan disembunyikan dari navigasi (§10).</p>
        <div class="mt-6 space-y-3">
          <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl"><span><span class="font-semibold text-sm">COGS (HPP)</span><span class="block text-xs text-slate-500">Klasifikasi expense sebagai COGS untuk Gross Profit</span></span><input type="checkbox" id="wCOGS" checked class="w-5 h-5 accent-slate-900"/></label>
          <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl"><span><span class="font-semibold text-sm">Assets</span><span class="block text-xs text-slate-500">Kelola aset & pembelian tidak kurangi profit</span></span><input type="checkbox" id="wAssets" checked class="w-5 h-5 accent-slate-900"/></label>
          <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl"><span><span class="font-semibold text-sm">Tax</span><span class="block text-xs text-slate-500">Basic tax config (tanpa e-Faktur)</span></span><input type="checkbox" id="wTax" class="w-5 h-5 accent-slate-900"/></label>
          <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl opacity-60"><span><span class="font-semibold text-sm">Receivable (Piutang) — V1.5</span><span class="block text-xs text-slate-500">Nice-to-have, tidak wajib MVP</span></span><input type="checkbox" id="wReceivable" class="w-5 h-5 accent-slate-900"/></label>
          <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl opacity-60"><span><span class="font-semibold text-sm">Payable (Hutang) — V1.5</span><span class="block text-xs text-slate-500">Nice-to-have, tidak wajib MVP</span></span><input type="checkbox" id="wPayable" class="w-5 h-5 accent-slate-900"/></label>
        </div>
      </div>

      <!-- Step 5 -->
      <div id="panel-5" class="hidden p-6 lg:p-8 text-center">
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 grid place-items-center mx-auto"><i data-lucide="check-circle-2" class="w-8 h-8"></i></div>
        <h2 class="font-display text-xl font-bold mt-4">Siap digunakan! 🎉</h2>
        <p class="text-sm text-slate-500 mt-2 max-w-[560px] mx-auto">Sistem akan membuat business, default accounts, categories, financial settings, dan Owner. Kamu akan diarahkan ke Dashboard.</p>
        <div id="finishSummary" class="mt-6 text-left bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm space-y-1"></div>
        <div class="mt-6 flex gap-3 justify-center">
          <button id="btnBackTo4" class="px-6 py-3 rounded-xl border border-slate-200 font-semibold text-sm">Kembali</button>
          <button id="btnFinish" class="px-8 py-3 rounded-xl bg-brand-700 hover:bg-brand-600 text-white font-bold text-sm">Selesai & Buka Dashboard →</button>
        </div>
      </div>

      <!-- Footer nav -->
      <div id="wizFooter" class="px-6 lg:px-8 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
        <button id="btnPrev" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white font-semibold text-sm disabled:opacity-40" disabled>Kembali</button>
        <div class="flex items-center gap-2"><span class="text-xs text-slate-500 hidden md:inline">Setup &lt;5 menit • Template tidak mengunci struktur (§9)</span><button id="btnNext" class="px-6 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-sm">Lanjut →</button></div>
      </div>
    </div>
  </div>
  <script src="{{ asset('assets/js/setup.js') }}"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
