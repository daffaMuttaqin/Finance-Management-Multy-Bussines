@extends('layouts.app')

@section('title', 'KeuKita — Dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
<section id="view-dashboard" class="space-y-6">
        <!-- Greeting + period -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
          <div>
            <h1 class="font-display text-[22px] font-bold tracking-tight">Selamat pagi, Owner 👋</h1>
            <p class="text-[13px] text-slate-500 mt-1">Ringkasan kesehatan finansial bisnis kamu hari ini. Data terisolasi per <span class="font-mono text-xs bg-slate-100 px-1.5 py-0.5 rounded">business_id</span></p>
          </div>
          <div class="flex items-center gap-2">
            <div class="flex bg-white border border-slate-200 rounded-xl p-1">
              <button data-range="today" class="range-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 text-white">Hari ini</button>
              <button data-range="week" class="range-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50">Minggu</button>
              <button data-range="month" class="range-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50">Bulan</button>
              <button data-range="year" class="range-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50">Tahun</button>
            </div>
            <button id="btnExportDash" class="h-9 px-3 rounded-xl bg-white border border-slate-200 text-xs font-semibold inline-flex items-center gap-1.5"><i data-lucide="download" class="w-4 h-4"></i> Export</button>
          </div>
        </div>

        <!-- Financial Health 4 cards -->
        <div>
          <h2 class="text-[11px] font-semibold tracking-[0.14em] text-slate-400 uppercase mb-3">Financial Health</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
              <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 grid place-items-center"><i data-lucide="wallet" class="w-5 h-5"></i></div>
                <span class="text-[11px] font-semibold tracking-wide bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full">Available Cash</span>
              </div>
              <div class="mt-4 text-[11px] font-semibold tracking-widest text-slate-400 uppercase">Uang Tersedia</div>
              <div id="statAvailableCash" class="font-display text-[22px] font-bold tracking-tight mt-1">Rp 0</div>
              <div id="statAvailableCashSub" class="text-xs text-slate-500 mt-1">dari 0 akun</div>
              <div class="mt-3 flex items-center gap-1 text-xs font-medium text-emerald-600"><i data-lucide="trending-up" class="w-3.5 h-3.5"></i> <span id="statCashChange">+0% vs bulan lalu</span></div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
              <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 grid place-items-center"><i data-lucide="line-chart" class="w-5 h-5"></i></div>
                <span class="text-[11px] font-semibold tracking-wide bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full">Net Profit</span>
              </div>
              <div class="mt-4 text-[11px] font-semibold tracking-widest text-slate-400 uppercase">Profit Bersih</div>
              <div id="statNetProfit" class="font-display text-[22px] font-bold tracking-tight mt-1">Rp 0</div>
              <div id="statNetProfitSub" class="text-xs text-slate-500 mt-1">Revenue - COGS - Expenses</div>
              <div class="mt-3 h-1.5 bg-slate-100 rounded-full overflow-hidden"><div id="barNetProfit" class="h-full bg-indigo-500 rounded-full" style="width: 40%"></div></div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
              <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 grid place-items-center"><i data-lucide="hand-coins" class="w-5 h-5"></i></div>
                <span class="text-[11px] font-semibold tracking-wide bg-amber-50 text-amber-700 px-2 py-1 rounded-full">Receivable</span>
              </div>
              <div class="mt-4 text-[11px] font-semibold tracking-widest text-slate-400 uppercase">Piutang</div>
              <div id="statReceivable" class="font-display text-[22px] font-bold tracking-tight mt-1">Rp 0</div>
              <div class="text-xs text-slate-500 mt-1">Uang di luar bisnis</div>
              <div class="mt-3 inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-700 px-2 py-1 rounded-full font-medium">V1.5 • Opsional</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
              <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 grid place-items-center"><i data-lucide="receipt" class="w-5 h-5"></i></div>
                <span class="text-[11px] font-semibold tracking-wide bg-rose-50 text-rose-700 px-2 py-1 rounded-full">Payable</span>
              </div>
              <div class="mt-4 text-[11px] font-semibold tracking-widest text-slate-400 uppercase">Hutang</div>
              <div id="statPayable" class="font-display text-[22px] font-bold tracking-tight mt-1">Rp 0</div>
              <div class="text-xs text-slate-500 mt-1">Kewajiban bisnis</div>
              <div class="mt-3 inline-flex items-center gap-1 text-xs bg-rose-50 text-rose-700 px-2 py-1 rounded-full font-medium">V1.5 • Opsional</div>
            </div>
          </div>
        </div>

        <!-- Middle grid: Cashflow chart + Summary -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
          <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-semibold text-[14px]">Cashflow • Uang Masuk vs Keluar</h3>
                <p class="text-xs text-slate-500">Actual cash movement, bukan profit semata (§24)</p>
              </div>
              <span class="text-[11px] font-semibold bg-slate-900 text-white px-2.5 py-1 rounded-full" id="cashflowNet">Net: Rp 0</span>
            </div>
            <div class="mt-4 h-[240px]"><canvas id="cashflowChart"></canvas></div>
            <div class="mt-4 grid grid-cols-3 gap-3 text-center">
              <div class="bg-emerald-50 rounded-xl py-3"><div class="text-[11px] font-semibold tracking-widest text-emerald-700 uppercase">Cash In</div><div id="cashInVal" class="font-bold text-sm mt-1">Rp 0</div></div>
              <div class="bg-rose-50 rounded-xl py-3"><div class="text-[11px] font-semibold tracking-widest text-rose-700 uppercase">Cash Out</div><div id="cashOutVal" class="font-bold text-sm mt-1">Rp 0</div></div>
              <div class="bg-slate-900 text-white rounded-xl py-3"><div class="text-[11px] font-semibold tracking-widest text-slate-300 uppercase">Net Cashflow</div><div id="cashNetVal" class="font-bold text-sm mt-1">Rp 0</div></div>
            </div>
          </div>

          <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
            <h3 class="font-semibold text-[14px]">Financial Summary</h3>
            <p class="text-xs text-slate-500">Sesuai §14 profit rules</p>
            <div class="mt-4 space-y-3">
              <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                <span class="text-sm text-slate-600 flex items-center gap-2"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Revenue</span>
                <span id="sumRevenue" class="font-semibold text-sm">Rp 0</span>
              </div>
              <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                <span class="text-sm text-slate-600 flex items-center gap-2"><span class="w-2 h-2 bg-amber-500 rounded-full"></span> COGS</span>
                <span id="sumCogs" class="font-semibold text-sm">Rp 0</span>
              </div>
              <div class="flex justify-between items-center py-2.5 border-b border-dashed border-slate-200 bg-slate-50 px-3 rounded-xl">
                <span class="text-sm font-semibold">Gross Profit</span>
                <span id="sumGross" class="font-bold text-sm">Rp 0</span>
              </div>
              <div class="flex justify-between items-center py-2.5 border-b border-slate-100">
                <span class="text-sm text-slate-600 flex items-center gap-2"><span class="w-2 h-2 bg-rose-500 rounded-full"></span> Operating Expense</span>
                <span id="sumOpex" class="font-semibold text-sm">Rp 0</span>
              </div>
              <div class="flex justify-between items-center py-3 bg-slate-900 text-white px-3 rounded-xl">
                <span class="text-sm font-semibold">Net Profit</span>
                <span id="sumNet" class="font-bold">Rp 0</span>
              </div>
              <p class="text-[11px] text-slate-500 leading-relaxed">Net Profit = Revenue - COGS - Profit-Affecting Expenses. Asset purchase & transfer tidak mengurangi profit (§15).</p>
            </div>
          </div>
        </div>

        <!-- Recent Transactions + Quick Actions -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
          <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
            <div class="p-5 flex items-center justify-between border-b border-slate-100">
              <h3 class="font-semibold text-[14px]">Transaksi Terbaru</h3>
              <div class="flex items-center gap-2">
                <select id="filterRecent" class="text-xs border border-slate-200 rounded-xl px-2.5 py-1.5 bg-white">
                  <option value="all">Semua</option>
                  <option value="INCOME">Uang Masuk</option>
                  <option value="EXPENSE">Uang Keluar</option>
                  <option value="TRANSFER">Transfer</option>
                </select>
                <button data-view-jump="income" class="text-xs font-semibold text-brand-700 hover:underline">Lihat semua</button>
              </div>
            </div>
            <div id="recentList" class="divide-y divide-slate-100 max-h-[420px] overflow-auto"></div>
            <div id="recentEmpty" class="hidden p-8 text-center">
              <div class="w-12 h-12 rounded-2xl bg-slate-100 grid place-items-center mx-auto"><i data-lucide="inbox" class="w-6 h-6 text-slate-400"></i></div>
              <div class="font-semibold mt-3">Belum ada transaksi</div>
              <p class="text-sm text-slate-500 mt-1">Mulai catat pemasukan atau pengeluaran pertamamu.</p>
              <button data-quick="income" class="mt-4 px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-semibold">+ Tambah Transaksi</button>
            </div>
          </div>

          <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
              <h3 class="font-semibold text-sm">Aksi Cepat</h3>
              <p class="text-xs text-slate-500 mt-1">Target &lt;30 detik per transaksi (§31)</p>
              <div class="mt-4 grid grid-cols-1 gap-2">
                <button data-quick="income" class="w-full flex items-center gap-3 p-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm">
                  <span class="w-8 h-8 bg-white/20 rounded-lg grid place-items-center"><i data-lucide="arrow-down-left" class="w-5 h-5"></i></span>
                  <span class="text-left"><span class="block leading-none">Uang Masuk</span><span class="text-xs font-normal opacity-90">Catat pemasukan</span></span>
                  <i data-lucide="chevron-right" class="w-4 h-4 ml-auto opacity-70"></i>
                </button>
                <button data-quick="expense" class="w-full flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 font-semibold text-sm">
                  <span class="w-8 h-8 bg-rose-50 text-rose-600 rounded-lg grid place-items-center"><i data-lucide="arrow-up-right" class="w-5 h-5"></i></span>
                  <span class="text-left"><span class="block leading-none">Uang Keluar</span><span class="text-xs font-normal text-slate-500">Catat pengeluaran</span></span>
                  <i data-lucide="chevron-right" class="w-4 h-4 ml-auto text-slate-400"></i>
                </button>
                <button data-quick="transfer" class="w-full flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 font-semibold text-sm">
                  <span class="w-8 h-8 bg-sky-50 text-sky-600 rounded-lg grid place-items-center"><i data-lucide="arrow-left-right" class="w-5 h-5"></i></span>
                  <span class="text-left"><span class="block leading-none">Transfer</span><span class="text-xs font-normal text-slate-500">Pindah antar akun</span></span>
                  <i data-lucide="chevron-right" class="w-4 h-4 ml-auto text-slate-400"></i>
                </button>
              </div>
            </div>

            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 text-white">
              <div class="text-xs tracking-widest uppercase opacity-70 font-semibold">Saldo per Akun</div>
              <div id="miniAccounts" class="mt-3 space-y-2"></div>
              <button data-view-jump="accounts" class="mt-4 w-full py-2 rounded-xl bg-white text-slate-900 text-xs font-bold">Kelola Accounts</button>
            </div>
          </div>
        </div>
      </section>

      <!-- INCOME -->
      <section id="view-income" class="hidden space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <h2 class="font-display text-xl font-bold">Uang Masuk • Income</h2>
            <p class="text-sm text-slate-500">Mencatat pemasukan (POSTED/VOIDED, audit trail §39-40)</p>
          </div>
          <button data-quick="income" class="h-10 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold inline-flex items-center gap-2 self-start md:self-auto"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Uang Masuk</button>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-3 flex flex-wrap gap-2 items-center">
          <div class="flex-1 min-w-[220px] flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
            <i data-lucide="search" class="w-4 h-4 text-slate-400"></i><input id="searchIncome" placeholder="Cari kategori, deskripsi..." class="bg-transparent outline-none text-sm w-full"/>
          </div>
          <select id="filterIncomeCat" class="border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white"><option value="">Semua Kategori</option></select>
          <select id="filterIncomeAcc" class="border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white"><option value="">Semua Akun</option></select>
          <select id="filterIncomeStatus" class="border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white"><option value="">Semua Status</option><option>POSTED</option><option>VOIDED</option></select>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
          <div class="overflow-auto">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-[11px] tracking-widest uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Tanggal</th><th class="text-left px-4 py-3">Kategori</th><th class="text-left px-4 py-3">Akun</th><th class="text-left px-4 py-3">Deskripsi</th><th class="text-right px-4 py-3">Jumlah</th><th class="text-center px-4 py-3">Status</th><th class="text-right px-4 py-3">Aksi</th></tr></thead>
              <tbody id="tbodyIncome" class="divide-y divide-slate-100"></tbody>
            </table>
          </div>
          <div id="emptyIncome" class="hidden p-10 text-center"><div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 grid place-items-center mx-auto"><i data-lucide="arrow-down-left" class="w-6 h-6"></i></div><div class="font-semibold mt-3">Belum ada uang masuk</div><p class="text-sm text-slate-500 mt-1">Catat penjualan pertama biar laporan keisi.</p><button data-quick="income" class="mt-4 px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold">+ Tambah</button></div>
        </div>
      </section>

      <!-- EXPENSE -->
      <section id="view-expense" class="hidden space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <h2 class="font-display text-xl font-bold">Uang Keluar • Expense</h2>
            <p class="text-sm text-slate-500">Kategori dengan <span class="font-mono text-xs bg-slate-100 px-1 py-0.5 rounded">affects_profit</span> akan mengurangi Net Profit (§15)</p>
          </div>
          <button data-quick="expense" class="h-10 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold inline-flex items-center gap-2 self-start md:self-auto"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Uang Keluar</button>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-3 flex flex-wrap gap-2 items-center">
          <div class="flex-1 min-w-[220px] flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
            <i data-lucide="search" class="w-4 h-4 text-slate-400"></i><input id="searchExpense" placeholder="Cari kategori, vendor..." class="bg-transparent outline-none text-sm w-full"/>
          </div>
          <select id="filterExpenseCat" class="border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white"><option value="">Semua Kategori</option></select>
          <select id="filterExpenseAcc" class="border border-slate-200 rounded-xl px-3 py-2 text-sm bg-white"><option value="">Semua Akun</option></select>
          <label class="inline-flex items-center gap-2 text-xs font-medium ml-2"><input type="checkbox" id="toggleOnlyProfit" class="rounded"/> Hanya affects_profit</label>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
          <div class="overflow-auto">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-[11px] tracking-widest uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Tanggal</th><th class="text-left px-4 py-3">Kategori</th><th class="text-left px-4 py-3">Akun</th><th class="text-left px-4 py-3">Deskripsi</th><th class="text-right px-4 py-3">Jumlah</th><th class="text-center px-4 py-3">Profit?</th><th class="text-right px-4 py-3">Aksi</th></tr></thead>
              <tbody id="tbodyExpense" class="divide-y divide-slate-100"></tbody>
            </table>
          </div>
          <div id="emptyExpense" class="hidden p-10 text-center"><div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 grid place-items-center mx-auto"><i data-lucide="arrow-up-right" class="w-6 h-6"></i></div><div class="font-semibold mt-3">Belum ada uang keluar</div><p class="text-sm text-slate-500 mt-1">Catat operasional, COGS, sewa, dll.</p><button data-quick="expense" class="mt-4 px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-semibold">+ Tambah</button></div>
        </div>
      </section>

      <!-- TRANSFER -->
      <section id="view-transfer" class="hidden space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <h2 class="font-display text-xl font-bold">Transfer Antar Akun</h2>
            <p class="text-sm text-slate-500">Transfer tidak mempengaruhi profit (§18, Rule 5)</p>
          </div>
          <button data-quick="transfer" class="h-10 px-4 rounded-xl bg-slate-900 hover:bg-black text-white text-sm font-semibold inline-flex items-center gap-2 self-start md:self-auto"><i data-lucide="arrow-left-right" class="w-4 h-4"></i> Transfer Baru</button>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
          <div class="overflow-auto">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-[11px] tracking-widest uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Tanggal</th><th class="text-left px-4 py-3">Dari → Ke</th><th class="text-left px-4 py-3">Deskripsi</th><th class="text-right px-4 py-3">Jumlah</th><th class="text-center px-4 py-3">Status</th><th class="text-right px-4 py-3">Aksi</th></tr></thead>
              <tbody id="tbodyTransfer" class="divide-y divide-slate-100"></tbody>
            </table>
          </div>
          <div id="emptyTransfer" class="hidden p-10 text-center"><div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 grid place-items-center mx-auto"><i data-lucide="arrow-left-right" class="w-6 h-6"></i></div><div class="font-semibold mt-3">Belum ada transfer</div><p class="text-sm text-slate-500">Misal BCA → Cash untuk setoran tunai.</p></div>
        </div>
      </section>

      <!-- ACCOUNTS -->
      <section id="view-accounts" class="hidden space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <h2 class="font-display text-xl font-bold">Accounts • Tempat Uang Disimpan</h2>
            <p class="text-sm text-slate-500">Cash / Bank / E-Wallet. Opening balance tidak dianggap revenue (§20)</p>
          </div>
          <button id="btnAddAccount" class="h-10 px-4 rounded-xl bg-slate-900 text-white text-sm font-semibold inline-flex items-center gap-2 self-start md:self-auto"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Account</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="gridAccounts"></div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
          <div class="p-4 flex items-center justify-between border-b border-slate-100">
            <h3 class="font-semibold text-sm">Daftar Accounts</h3>
            <span id="totalAccountsBalance" class="text-xs font-bold bg-slate-900 text-white px-2.5 py-1 rounded-full">Total: Rp 0</span>
          </div>
          <div class="overflow-auto">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-[11px] tracking-widest uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Nama</th><th class="text-left px-4 py-3">Tipe</th><th class="text-right px-4 py-3">Opening</th><th class="text-right px-4 py-3">Saldo Saat Ini</th><th class="text-center px-4 py-3">Status</th><th class="text-right px-4 py-3">Aksi</th></tr></thead>
              <tbody id="tbodyAccounts" class="divide-y divide-slate-100"></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ASSETS -->
      <section id="view-assets" class="hidden space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div>
            <h2 class="font-display text-xl font-bold">Assets • Barang Bernilai</h2>
            <p class="text-sm text-slate-500">Pembelian asset mengurangi cash tapi tidak mengurangi profit (§21)</p>
          </div>
          <button id="btnAddAsset" class="h-10 px-4 rounded-xl bg-slate-900 text-white text-sm font-semibold inline-flex items-center gap-2 self-start md:self-auto"><i data-lucide="plus" class="w-4 h-4"></i> Tambah Asset</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="gridAssets"></div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
          <div class="overflow-auto">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-[11px] tracking-widest uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Asset</th><th class="text-left px-4 py-3">Akun</th><th class="text-left px-4 py-3">Tanggal Beli</th><th class="text-right px-4 py-3">Harga</th><th class="text-center px-4 py-3">Status</th></tr></thead>
              <tbody id="tbodyAssets" class="divide-y divide-slate-100"></tbody>
            </table>
          </div>
          <div id="emptyAssets" class="hidden p-10 text-center"><div class="w-12 h-12 rounded-2xl bg-slate-100 grid place-items-center mx-auto"><i data-lucide="package" class="w-6 h-6 text-slate-400"></i></div><div class="font-semibold mt-3">Belum ada asset</div><p class="text-sm text-slate-500">Contoh: oven, kulkas, laptop, motor.</p></div>
        </div>
      </section>

      <!-- REPORTS -->
      <section id="view-reports" class="hidden space-y-4">
        <div class="flex flex-wrap items-end justify-between gap-3">
          <div>
            <h2 class="font-display text-xl font-bold">Reports</h2>
            <p class="text-sm text-slate-500">Filter mengikuti §33, export mengikuti filter aktif (§34)</p>
          </div>
          <div class="flex gap-2">
            <button id="btnExportExcel" class="h-9 px-3 rounded-xl bg-emerald-600 text-white text-xs font-bold inline-flex items-center gap-1.5"><i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export Excel</button>
            <button id="btnExportPdf" class="h-9 px-3 rounded-xl bg-white border border-slate-200 text-xs font-bold inline-flex items-center gap-1.5"><i data-lucide="file-text" class="w-4 h-4"></i> Export PDF</button>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-card">
          <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div class="md:col-span-2">
              <label class="text-[11px] font-semibold tracking-widest uppercase text-slate-500">Date preset</label>
              <select id="reportPreset" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white">
                <option value="all">Semua</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month" selected>This Month</option>
                <option value="lastMonth">Last Month</option>
                <option value="year">This Year</option>
                <option value="custom">Custom Range</option>
              </select>
            </div>
            <div>
              <label class="text-[11px] font-semibold tracking-widest uppercase text-slate-500">Tipe</label>
              <select id="reportType" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white"><option value="all">Semua</option><option value="INCOME">Income</option><option value="EXPENSE">Expense</option><option value="TRANSFER">Transfer</option></select>
            </div>
            <div>
              <label class="text-[11px] font-semibold tracking-widest uppercase text-slate-500">Kategori</label>
              <select id="reportCategory" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white"><option value="">Semua</option></select>
            </div>
            <div>
              <label class="text-[11px] font-semibold tracking-widest uppercase text-slate-500">Akun</label>
              <select id="reportAccount" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white"><option value="">Semua</option></select>
            </div>
            <div class="flex items-end"><button id="btnApplyReport" class="w-full h-[42px] rounded-xl bg-slate-900 text-white text-sm font-semibold">Terapkan</button></div>
          </div>
          <div id="reportCustomRange" class="hidden mt-3 grid grid-cols-2 gap-3">
            <div><label class="text-xs font-medium">Dari</label><input type="date" id="reportFrom" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm"/></div>
            <div><label class="text-xs font-medium">Sampai</label><input type="date" id="reportTo" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm"/></div>
          </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
          <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
            <h3 class="font-semibold text-sm">Financial Summary (filtered)</h3>
            <div class="mt-4 space-y-2 text-sm">
              <div class="flex justify-between"><span class="text-slate-600">Revenue</span><span id="rptRevenue" class="font-semibold">Rp 0</span></div>
              <div class="flex justify-between"><span class="text-slate-600">COGS</span><span id="rptCogs" class="font-semibold">Rp 0</span></div>
              <div class="flex justify-between font-bold border-y border-dashed py-2"><span>Gross Profit</span><span id="rptGross">Rp 0</span></div>
              <div class="flex justify-between"><span class="text-slate-600">Expenses (affects profit)</span><span id="rptOpex" class="font-semibold">Rp 0</span></div>
              <div class="flex justify-between bg-slate-900 text-white px-3 py-2 rounded-xl font-bold"><span>Net Profit</span><span id="rptNet">Rp 0</span></div>
              <div class="flex justify-between pt-2 border-t"><span class="text-slate-600">Net Cashflow</span><span id="rptCash" class="font-semibold">Rp 0</span></div>
            </div>
          </div>
          <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
            <h3 class="font-semibold text-sm">Cashflow (filtered)</h3>
            <div class="mt-4 h-[220px]"><canvas id="reportChart"></canvas></div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
          <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-semibold text-sm">Transaction Report</h3>
            <span id="rptCount" class="text-xs bg-slate-100 px-2 py-1 rounded-full font-semibold">0 transaksi</span>
          </div>
          <div class="overflow-auto">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-[11px] tracking-widest uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Tanggal</th><th class="text-left px-4 py-3">Tipe</th><th class="text-left px-4 py-3">Kategori</th><th class="text-left px-4 py-3">Akun</th><th class="text-right px-4 py-3">Jumlah</th><th class="text-center px-4 py-3">Status</th></tr></thead>
              <tbody id="tbodyReport" class="divide-y divide-slate-100"></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- SETTINGS -->
      <section id="view-settings" class="hidden space-y-4">
        <h2 class="font-display text-xl font-bold">Settings</h2>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
          <div class="xl:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
              <h3 class="font-semibold text-sm flex items-center gap-2"><i data-lucide="building-2" class="w-4 h-4"></i> Business Profile</h3>
              <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="text-xs font-semibold">Nama Bisnis</label><input id="setBizName" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm"/></div>
                <div><label class="text-xs font-semibold">Tipe Bisnis</label><select id="setBizType" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white"><option>Coffee Shop</option><option>Bakery / Patisserie</option><option>Restaurant</option><option>Catering</option><option>Travel</option><option>Retail</option><option>Salon / Barbershop</option><option>Laundry</option><option>Services</option><option>Online Shop</option><option>Other</option></select></div>
                <div><label class="text-xs font-semibold">Currency</label><select id="setCurrency" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white"><option>IDR (Rp)</option><option>USD ($)</option><option>SGD (S$)</option></select></div>
                <div><label class="text-xs font-semibold">Timezone</label><select id="setTimezone" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm bg-white"><option>Asia/Jakarta</option><option>Asia/Makassar</option><option>Asia/Jayapura</option></select></div>
              </div>
              <button id="btnSaveBusiness" class="mt-4 px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold">Simpan Perubahan</button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
              <h3 class="font-semibold text-sm flex items-center gap-2"><i data-lucide="tags" class="w-4 h-4"></i> Categories</h3>
              <div class="mt-3 flex gap-2">
                <button data-cat-tab="income" class="cat-tab px-3 py-1.5 rounded-full text-xs font-bold bg-slate-900 text-white">Income</button>
                <button data-cat-tab="expense" class="cat-tab px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100">Expense</button>
                <button id="btnAddCategory" class="ml-auto text-xs font-semibold border border-slate-200 px-3 py-1.5 rounded-full hover:bg-slate-50">+ Kategori</button>
              </div>
              <div id="listCategories" class="mt-4 space-y-2"></div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
              <h3 class="font-semibold text-sm flex items-center gap-2"><i data-lucide="sliders-horizontal" class="w-4 h-4"></i> Financial Settings</h3>
              <div class="mt-4 space-y-3">
                <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl"><span class="text-sm font-medium flex items-center gap-2"><i data-lucide="flame" class="w-4 h-4 text-amber-600"></i> Aktifkan COGS (HPP)</span><input type="checkbox" id="featCOGS" class="w-5 h-5 accent-slate-900" checked/></label>
                <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl"><span class="text-sm font-medium flex items-center gap-2"><i data-lucide="package" class="w-4 h-4"></i> Aktifkan Assets</span><input type="checkbox" id="featAssets" class="w-5 h-5 accent-slate-900" checked/></label>
                <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl"><span class="text-sm font-medium">Aktifkan Tax</span><input type="checkbox" id="featTax" class="w-5 h-5 accent-slate-900"/></label>
                <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl"><span class="text-sm font-medium">Aktifkan Piutang (Receivable) <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-bold">V1.5</span></span><input type="checkbox" id="featReceivable" class="w-5 h-5 accent-slate-900"/></label>
                <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl"><span class="text-sm font-medium">Aktifkan Hutang (Payable) <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-bold">V1.5</span></span><input type="checkbox" id="featPayable" class="w-5 h-5 accent-slate-900"/></label>
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
              <h3 class="font-semibold text-sm">Users</h3>
              <div class="mt-3 space-y-3">
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                  <img src="https://api.dicebear.com/7.x/initials/svg?seed=Owner" class="w-8 h-8 rounded-full"/><div class="flex-1"><div class="text-sm font-semibold">Owner</div><div class="text-xs text-slate-500">OWNER • owner@keukita.id</div></div><span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full font-bold">ACTIVE</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-dashed">
                  <div class="w-8 h-8 rounded-full bg-slate-100 grid place-items-center"><i data-lucide="user-plus" class="w-4 h-4"></i></div><div class="flex-1"><div class="text-sm font-semibold">Invite Admin</div><div class="text-xs text-slate-500">Kelola operasional harian</div></div><button class="text-xs font-bold border border-slate-200 px-3 py-1.5 rounded-full">Invite</button>
                </div>
              </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
              <h3 class="font-semibold text-sm flex items-center gap-2"><i data-lucide="shield-alert" class="w-4 h-4 text-amber-600"></i> Bahaya</h3>
              <p class="text-xs text-amber-800 mt-2 leading-relaxed">Reset akan menghapus semua transaksi demo lokal. Tidak ada hard delete untuk transaksi POSTED di production — gunakan VOID.</p>
              <button id="btnDangerReset" class="mt-3 w-full py-2 rounded-xl bg-white border border-amber-300 text-amber-700 text-xs font-bold">Reset Data Demo</button>
            </div>
          </div>
        </div>
      </section>

      <!-- AUDIT -->
      <section id="view-audit" class="hidden space-y-4">
        <h2 class="font-display text-xl font-bold">Audit Trail</h2>
        <p class="text-sm text-slate-500">Mencatat login, create/update/void transaksi, perubahan akun/kategori (§39)</p>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
          <div class="overflow-auto">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-[11px] tracking-widest uppercase text-slate-500"><tr><th class="text-left px-4 py-3">Waktu</th><th class="text-left px-4 py-3">User</th><th class="text-left px-4 py-3">Action</th><th class="text-left px-4 py-3">Entity</th><th class="text-left px-4 py-3">Detail</th></tr></thead>
              <tbody id="tbodyAudit" class="divide-y divide-slate-100"></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- Receivable/Payable placeholders -->
      <section id="view-receivable" class="hidden">
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-10 text-center">
          <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 grid place-items-center mx-auto"><i data-lucide="hand-coins" class="w-6 h-6"></i></div>
          <div class="font-bold mt-3">Piutang — V1.5</div><p class="text-sm text-slate-500 mt-1">Fitur ini akan hadir di fase berikutnya. Saat ini revenue yang belum diterima belum di-tracking sebagai receivable.</p>
        </div>
      </section>
      <section id="view-payable" class="hidden">
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-10 text-center">
          <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 grid place-items-center mx-auto"><i data-lucide="receipt" class="w-6 h-6"></i></div>
          <div class="font-bold mt-3">Hutang — V1.5</div><p class="text-sm text-slate-500 mt-1">Fitur payable akan hadir di fase berikutnya.</p>
        </div>
      </section>

<!-- MODAL: Transaction -->
  <div id="modalTx" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-close-tx></div>
    <div class="absolute inset-0 grid place-items-center p-4">
      <div class="w-full max-w-[560px] bg-white rounded-2xl shadow-float overflow-hidden max-h-[92vh] flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
          <div>
            <div id="txModalTitle" class="font-bold">Tambah Transaksi</div>
            <div id="txModalSub" class="text-xs text-slate-500">Pilih jenis: Uang Masuk / Keluar / Transfer</div>
          </div>
          <button data-close-tx class="w-8 h-8 grid place-items-center rounded-xl hover:bg-slate-100"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <!-- Type picker -->
        <div id="txTypePicker" class="px-6 pt-4 grid grid-cols-3 gap-2 shrink-0">
          <button data-tx-type="INCOME" class="tx-type-btn py-3 rounded-xl border-2 border-emerald-500 bg-emerald-50 text-emerald-700 font-bold text-sm">Uang Masuk</button>
          <button data-tx-type="EXPENSE" class="tx-type-btn py-3 rounded-xl border border-slate-200 bg-white font-semibold text-sm">Uang Keluar</button>
          <button data-tx-type="TRANSFER" class="tx-type-btn py-3 rounded-xl border border-slate-200 bg-white font-semibold text-sm">Transfer</button>
        </div>

        <form id="formTx" class="p-6 space-y-4 overflow-auto">
          <!-- common -->
          <div id="fieldAmount" class="space-y-2">
            <label class="text-xs font-semibold">Jumlah *</label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-500">Rp</span>
              <input id="txAmount" type="text" inputmode="numeric" placeholder="500.000" class="w-full border border-slate-200 rounded-xl pl-10 pr-3 py-3 text-lg font-bold outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10" required/>
            </div>
            <p id="errAmount" class="hidden text-xs text-rose-600">Jumlah wajib diisi dan lebih dari 0.</p>
          </div>

          <div id="groupIncomeExpense" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-xs font-semibold">Kategori *</label>
              <select id="txCategory" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"></select>
            </div>
            <div>
              <label class="text-xs font-semibold">Akun *</label>
              <select id="txAccount" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"></select>
            </div>
          </div>

          <div id="groupTransfer" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-xs font-semibold">Dari Akun *</label>
              <select id="txFrom" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"></select>
            </div>
            <div>
              <label class="text-xs font-semibold">Ke Akun *</label>
              <select id="txTo" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"></select>
            </div>
          </div>

          <div>
            <label class="text-xs font-semibold">Tanggal *</label>
            <input id="txDate" type="date" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm" required/>
          </div>

          <div>
            <label class="text-xs font-semibold">Deskripsi</label>
            <input id="txDesc" placeholder="Contoh: Penjualan kopi sore" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm"/>
          </div>

          <details class="border border-slate-200 rounded-xl">
            <summary class="px-4 py-3 text-sm font-semibold cursor-pointer select-none">Field opsional</summary>
            <div class="px-4 pb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
              <div><label class="text-xs font-semibold">Customer / Vendor</label><input id="txParty" placeholder="Nama" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm"/></div>
              <div><label class="text-xs font-semibold">No. Referensi</label><input id="txRef" placeholder="INV-001" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm"/></div>
            </div>
          </details>

          <div id="profitHint" class="hidden text-xs leading-relaxed bg-amber-50 border border-amber-200 rounded-xl p-3"></div>

          <div class="flex gap-2 pt-2">
            <button type="button" data-close-tx class="flex-1 py-3 rounded-xl border border-slate-200 font-semibold text-sm">Batal</button>
            <button type="submit" class="flex-1 py-3 rounded-xl bg-slate-900 text-white font-bold text-sm">Simpan Transaksi</button>
          </div>
          <p class="text-[11px] text-slate-500 text-center">Transaksi POSTED akan langsung mempengaruhi saldo & laporan. Void untuk reversal (§40).</p>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL: Account -->
  <div id="modalAccount" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-close-acc></div>
    <div class="absolute inset-0 grid place-items-center p-4">
      <div class="w-full max-w-[480px] bg-white rounded-2xl shadow-float overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
          <div class="font-bold" id="accModalTitle">Tambah Account</div>
          <button data-close-acc class="w-8 h-8 grid place-items-center rounded-xl hover:bg-slate-100"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="formAccount" class="p-6 space-y-4">
          <input type="hidden" id="accId"/>
          <div><label class="text-xs font-semibold">Nama Akun *</label><input id="accName" placeholder="BCA, Cash, QRIS..." class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm" required/></div>
          <div class="grid grid-cols-2 gap-4">
            <div><label class="text-xs font-semibold">Tipe</label><select id="accType" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"><option>Cash</option><option>Bank</option><option>E-Wallet</option><option>Other</option></select></div>
            <div><label class="text-xs font-semibold">Opening Balance</label><input id="accOpening" type="text" inputmode="numeric" placeholder="0" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm"/></div>
          </div>
          <p class="text-[11px] text-slate-500">Opening balance tidak dihitung sebagai revenue (§20).</p>
          <div class="flex gap-2"><button type="button" data-close-acc class="flex-1 py-3 rounded-xl border border-slate-200 font-semibold text-sm">Batal</button><button type="submit" class="flex-1 py-3 rounded-xl bg-slate-900 text-white font-bold text-sm">Simpan</button></div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL: Asset -->
  <div id="modalAsset" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-close-asset></div>
    <div class="absolute inset-0 grid place-items-center p-4">
      <div class="w-full max-w-[520px] bg-white rounded-2xl shadow-float overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
          <div class="font-bold">Tambah Asset</div>
          <button data-close-asset class="w-8 h-8 grid place-items-center rounded-xl hover:bg-slate-100"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="formAsset" class="p-6 space-y-4">
          <div><label class="text-xs font-semibold">Nama Asset *</label><input id="assetName" placeholder="Oven, Kulkas 2 pintu..." class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm" required/></div>
          <div class="grid grid-cols-2 gap-4">
            <div><label class="text-xs font-semibold">Harga Beli *</label><input id="assetPrice" type="text" inputmode="numeric" placeholder="5.000.000" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm font-bold" required/></div>
            <div><label class="text-xs font-semibold">Akun Pembayaran</label><select id="assetAccount" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"></select></div>
          </div>
          <div><label class="text-xs font-semibold">Tanggal Beli</label><input id="assetDate" type="date" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm"/></div>
          <div><label class="text-xs font-semibold">Deskripsi</label><input id="assetDesc" placeholder="Optional" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm"/></div>
          <div class="flex gap-2"><button type="button" data-close-asset class="flex-1 py-3 rounded-xl border border-slate-200 font-semibold text-sm">Batal</button><button type="submit" class="flex-1 py-3 rounded-xl bg-slate-900 text-white font-bold text-sm">Simpan Asset</button></div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL: Category -->
  <div id="modalCategory" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-close-cat></div>
    <div class="absolute inset-0 grid place-items-center p-4">
      <div class="w-full max-w-[480px] bg-white rounded-2xl shadow-float overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
          <div class="font-bold">Tambah Kategori</div>
          <button data-close-cat class="w-8 h-8 grid place-items-center rounded-xl hover:bg-slate-100"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="formCategory" class="p-6 space-y-4">
          <div><label class="text-xs font-semibold">Nama *</label><input id="catName" placeholder="Bahan Baku, Sewa..." class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm" required/></div>
          <div class="grid grid-cols-2 gap-4">
            <div><label class="text-xs font-semibold">Tipe</label><select id="catType" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"><option value="INCOME">Income</option><option value="EXPENSE" selected>Expense</option></select></div>
            <div id="wrapClassification"><label class="text-xs font-semibold">Klasifikasi</label><select id="catClass" class="mt-1 w-full border border-slate-200 rounded-xl px-3 py-3 text-sm bg-white"><option>COGS</option><option>Operational</option><option>Marketing</option><option>Salary</option><option>Rent</option><option>Utilities</option><option>Other</option><option>Asset</option></select></div>
          </div>
          <label id="wrapAffects" class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl"><input type="checkbox" id="catAffects" checked class="w-5 h-5 accent-slate-900"/><span class="text-sm font-medium">Mempengaruhi profit (<span class="font-mono text-xs">affects_profit</span>)</span></label>
          <div class="flex gap-2"><button type="button" data-close-cat class="flex-1 py-3 rounded-xl border border-slate-200 font-semibold text-sm">Batal</button><button type="submit" class="flex-1 py-3 rounded-xl bg-slate-900 text-white font-bold text-sm">Simpan</button></div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL: Confirm Void/Archive -->
  <div id="modalConfirm" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-close-confirm></div>
    <div class="absolute inset-0 grid place-items-center p-4">
      <div class="w-full max-w-[440px] bg-white rounded-2xl shadow-float p-6 text-center">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 grid place-items-center mx-auto"><i data-lucide="triangle-alert" class="w-6 h-6"></i></div>
        <div id="confirmTitle" class="font-bold mt-3">Void transaksi?</div>
        <p id="confirmDesc" class="text-sm text-slate-600 mt-2 leading-relaxed">Tindakan ini akan membatalkan dampak finansial dan mencatat audit trail. Status menjadi VOIDED.</p>
        <div class="mt-5 grid grid-cols-2 gap-3">
          <button data-close-confirm class="py-3 rounded-xl border border-slate-200 font-semibold text-sm">Batal</button>
          <button id="btnConfirmOk" class="py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm">Ya, Void</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden">
    <div class="bg-slate-900 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-float flex items-center gap-3">
      <span class="w-7 h-7 rounded-lg bg-white/15 grid place-items-center"><i data-lucide="check" class="w-4 h-4"></i></span>
      <span id="toastMsg">Berhasil disimpan</span>
    </div>
  </div>

  
  



@endsection

@push('scripts')
<script src="{{ asset('assets/js/app.js') }}"></script>
<script>lucide.createIcons();</script>
@if(isset($business))
<script>
  window._serverData = {
    business: @json($business),
    accounts: @json($accounts ?? []),
    categories: @json($categories ?? []),
    transactions: @json($transactions ?? []),
    assets: @json($assets ?? []),
    metrics: @json($metrics ?? null),
    available: @json($available ?? 0)
  };
  console.log('[KeuKita] Hydrated from DB:', window._serverData);
</script>
@endif
@endpush
