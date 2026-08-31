<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Keuangan — {{ $business->name }}</title>
<style>
  body{font-family: Inter, sans-serif; color:#0f172a; font-size:12px}
  h1{font-size:18px; margin:0}
  .muted{color:#64748b}
  table{width:100%; border-collapse:collapse; margin-top:12px}
  th{ background:#0f172a; color:#fff; text-align:left; padding:8px; font-size:10px; text-transform:uppercase; letter-spacing:0.08em }
  td{ padding:7px 8px; border-bottom:1px solid #e2e8f0 }
  .right{text-align:right}
  .badge{ display:inline-block; font-size:10px; font-weight:700; padding:2px 6px; border-radius:999px }
  .badge-indo{background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0}
  .badge-exp{background:#fff1f2; color:#9f1239; border:1px solid #fecdd3}
  .summary{ display:grid; grid-template-columns: repeat(4, 1fr); gap:10px; margin-top:14px}
  .card{ border:1px solid #e2e8f0; border-radius:12px; padding:10px }
  @media print { .no-print{display:none} }
</style>
</head>
<body>
  <div style="display:flex; justify-content:space-between; align-items:center">
    <div>
      <h1>KeuKita — {{ $business->name }} <span class="muted" style="font-weight:400">({{ $business->type }})</span></h1>
      <div class="muted">Laporan Keuangan • {{ $generatedAt }} • Filter: {{ json_encode($filters) }}</div>
    </div>
    <button onclick="window.print()" class="no-print" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:10px; background:#fff; cursor:pointer">Cetak / Simpan PDF</button>
  </div>

  <div class="summary">
    <div class="card"><div class="muted" style="font-size:10px; letter-spacing:0.12em; font-weight:700">REVENUE</div><div style="font-weight:700; font-size:14px">Rp {{ number_format($metrics['revenue'] ?? 0,0,',','.') }}</div></div>
    <div class="card"><div class="muted" style="font-size:10px; letter-spacing:0.12em; font-weight:700">COGS</div><div style="font-weight:700; font-size:14px">Rp {{ number_format($metrics['cogs'] ?? 0,0,',','.') }}</div></div>
    <div class="card"><div class="muted" style="font-size:10px; letter-spacing:0.12em; font-weight:700">GROSS</div><div style="font-weight:700; font-size:14px">Rp {{ number_format($metrics['gross'] ?? 0,0,',','.') }}</div></div>
    <div class="card" style="background:#0f172a; color:#fff"><div style="font-size:10px; letter-spacing:0.12em; font-weight:700; opacity:0.7">NET PROFIT</div><div style="font-weight:700; font-size:14px">Rp {{ number_format($metrics['net'] ?? 0,0,',','.') }}</div></div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Tipe</th>
        <th>Kategori</th>
        <th>Akun</th>
        <th class="right">Jumlah</th>
        <th>Deskripsi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($transactions as $t)
        <tr>
          <td>{{ $t->transaction_date->format('Y-m-d') }}</td>
          <td><span class="badge {{ $t->type==='INCOME'?'badge-indo':'badge-exp' }}">{{ $t->type }}</span></td>
          <td>{{ $t->category?->name ?? '-' }}</td>
          <td>{{ $t->type==='TRANSFER' ? ($t->fromAccount?->name.' → '.$t->toAccount?->name) : ($t->account?->name ?? '-') }}</td>
          <td class="right" style="font-weight:700">Rp {{ number_format($t->amount,0,',','.') }}</td>
          <td>{{ $t->description }}</td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align:center; padding:20px" class="muted">Tidak ada transaksi untuk filter ini.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="muted" style="margin-top:14px; font-size:10px">Dicetak dari KeuKita • Business ID: {{ $business->id }} • {{ $generatedAt }}</div>
</body>
</html>
