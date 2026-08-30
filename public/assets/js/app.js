/* KeuKita - Frontend Mock Financial Engine | HTML/CSS/JS MVP
   PRD compliance: §§11-42 financial rules, audit, void, etc.
*/
const KEY = 'keukita_v2';
const fmtIDR = (n) => new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(Math.round(n||0));
const fmtNum = (n) => new Intl.NumberFormat('id-ID').format(Math.round(n||0));
const parseAmount = (s) => {
  if(!s) return 0;
  const cleaned = String(s).replace(/[^0-9]/g,'');
  return parseInt(cleaned||'0',10);
};
const formatInputAmount = (el) => {
  const v = parseAmount(el.value);
  el.value = v ? fmtNum(v) : '';
};
const uid = () => Math.random().toString(36).slice(2,9);
const todayISO = () => new Date().toISOString().slice(0,10);
const nowISO = () => new Date().toISOString();

// Templates per PRD §9
const TEMPLATES = {
  "Coffee Shop": {
    income: [{name:"Coffee Sales",class:"Sales"},{name:"Food Sales",class:"Sales"},{name:"Other Sales",class:"Other"}],
    expense: [{name:"Raw Material",class:"COGS",affects:true},{name:"Salary",class:"Salary",affects:true},{name:"Rent",class:"Rent",affects:true},{name:"Electricity",class:"Utilities",affects:true},{name:"Marketing",class:"Marketing",affects:true},{name:"Other Expense",class:"Other",affects:true}],
    accounts: [{name:"Cash",type:"Cash",opening:2000000},{name:"Bank BCA",type:"Bank",opening:8000000},{name:"QRIS",type:"E-Wallet",opening:1500000}]
  },
  "Bakery / Patisserie": {
    income: [{name:"Cake Sales",class:"Sales"},{name:"Dessert Sales",class:"Sales"},{name:"Catering",class:"Sales"},{name:"Other Sales",class:"Other"}],
    expense: [{name:"Ingredients",class:"COGS",affects:true},{name:"Packaging",class:"COGS",affects:true},{name:"Salary",class:"Salary",affects:true},{name:"Rent",class:"Rent",affects:true},{name:"Marketing",class:"Marketing",affects:true},{name:"Other Expense",class:"Other",affects:true}],
    accounts: [{name:"Cash",type:"Cash",opening:1500000},{name:"Bank BCA",type:"Bank",opening:10000000},{name:"QRIS",type:"E-Wallet",opening:1200000}]
  },
  "Travel": {
    income: [{name:"Tour",class:"Sales"},{name:"Ticket",class:"Sales"},{name:"Transportation",class:"Sales"},{name:"Other Income",class:"Other"}],
    expense: [{name:"Ticket Cost",class:"COGS",affects:true},{name:"Hotel",class:"COGS",affects:true},{name:"Transportation",class:"COGS",affects:true},{name:"Commission",class:"Operational",affects:true},{name:"Marketing",class:"Marketing",affects:true},{name:"Other Expense",class:"Other",affects:true}],
    accounts: [{name:"Cash",type:"Cash",opening:3000000},{name:"Bank BCA",type:"Bank",opening:12000000},{name:"E-Wallet",type:"E-Wallet",opening:2000000}]
  },
  "Retail": {
    income: [{name:"Product Sales",class:"Sales"},{name:"Other Sales",class:"Other"}],
    expense: [{name:"Product Cost",class:"COGS",affects:true},{name:"Salary",class:"Salary",affects:true},{name:"Rent",class:"Rent",affects:true},{name:"Utilities",class:"Utilities",affects:true},{name:"Marketing",class:"Marketing",affects:true},{name:"Other Expense",class:"Other",affects:true}],
    accounts: [{name:"Cash",type:"Cash",opening:2500000},{name:"Bank",type:"Bank",opening:9000000},{name:"QRIS",type:"E-Wallet",opening:1000000}]
  },
  "Services": {
    income: [{name:"Service Revenue",class:"Sales"},{name:"Other Income",class:"Other"}],
    expense: [{name:"Operational",class:"Operational",affects:true},{name:"Salary",class:"Salary",affects:true},{name:"Software",class:"Operational",affects:true},{name:"Marketing",class:"Marketing",affects:true},{name:"Rent",class:"Rent",affects:true},{name:"Other Expense",class:"Other",affects:true}],
    accounts: [{name:"Cash",type:"Cash",opening:2000000},{name:"Bank",type:"Bank",opening:7000000},{name:"E-Wallet",type:"E-Wallet",opening:800000}]
  },
  "Other": {
    income: [{name:"Sales",class:"Sales"},{name:"Other Income",class:"Other"}],
    expense: [{name:"COGS",class:"COGS",affects:true},{name:"Operational",class:"Operational",affects:true},{name:"Marketing",class:"Marketing",affects:true},{name:"Salary",class:"Salary",affects:true},{name:"Rent",class:"Rent",affects:true},{name:"Other Expense",class:"Other",affects:true}],
    accounts: [{name:"Cash",type:"Cash",opening:2000000},{name:"Bank",type:"Bank",opening:5000000}]
  }
};

function defaultState() {
  const tmpl = TEMPLATES["Coffee Shop"];
  const business = {
    id: 'biz_'+uid(),
    name: 'Kopi Sore',
    type: 'Coffee Shop',
    logo: 'https://api.dicebear.com/7.x/shapes/svg?seed=kopi',
    currency: 'IDR (Rp)',
    timezone: 'Asia/Jakarta'
  };
  const accounts = tmpl.accounts.map(a=>({id:'acc_'+uid(), name:a.name, type:a.type, opening: a.opening, archived:false}));
  const categories = [
    ...tmpl.income.map(c=>({id:'cat_'+uid(), name:c.name, type:'INCOME', classification:c.class, affects_profit:true, archived:false})),
    ...tmpl.expense.map(c=>({id:'cat_'+uid(), name:c.name, type:'EXPENSE', classification:c.class, affects_profit: c.affects!==false, archived:false})),
    // special non-profit categories
    {id:'cat_'+uid(), name:'Asset Purchase', type:'EXPENSE', classification:'Asset', affects_profit:false, archived:false},
    {id:'cat_'+uid(), name:'Owner Withdrawal', type:'EXPENSE', classification:'Other', affects_profit:false, archived:false},
  ];
  // sample transactions
  const txs = [
    {id:'tx_'+uid(), business_id: business.id, type:'INCOME', status:'POSTED', category_id: categories[0].id, account_id: accounts[1].id, amount: 4500000, transaction_date: new Date(Date.now()-86400000*2).toISOString().slice(0,10), description:'Penjualan harian', reference:'', party:'', created_at: nowISO()},
    {id:'tx_'+uid(), business_id: business.id, type:'INCOME', status:'POSTED', category_id: categories[0].id, account_id: accounts[2].id, amount: 1200000, transaction_date: new Date(Date.now()-86400000*1).toISOString().slice(0,10), description:'QRIS sore', reference:'', party:'', created_at: nowISO()},
    {id:'tx_'+uid(), business_id: business.id, type:'EXPENSE', status:'POSTED', category_id: categories.find(c=>c.name==='Raw Material').id, account_id: accounts[1].id, amount: 1800000, transaction_date: new Date(Date.now()-86400000*1).toISOString().slice(0,10), description:'Biji kopi & susu', reference:'', party:'Supplier Kopi', created_at: nowISO()},
    {id:'tx_'+uid(), business_id: business.id, type:'EXPENSE', status:'POSTED', category_id: categories.find(c=>c.name==='Rent').id, account_id: accounts[1].id, amount: 2500000, transaction_date: new Date(Date.now()-86400000*5).toISOString().slice(0,10), description:'Sewa tempat bulan ini', reference:'', party:'', created_at: nowISO()},
    {id:'tx_'+uid(), business_id: business.id, type:'TRANSFER', status:'POSTED', from_account_id: accounts[1].id, to_account_id: accounts[0].id, amount: 2000000, transaction_date: todayISO(), description:'Setoran BCA ke Cash', reference:'', party:'', created_at: nowISO()},
  ];
  const assets = [
    {id:'as_'+uid(), name:'Mesin Espresso', category:'Machine', purchase_date: new Date(Date.now()-86400000*30).toISOString().slice(0,10), purchase_price: 15000000, account_id: accounts[1].id, description:'La Marzocco', status:'ACTIVE'}
  ];
  return {
    business, accounts, categories, transactions: txs, assets,
    audit: [{id:'au_'+uid(), business_id: business.id, user:'Owner', action:'BUSINESS_CREATED', entity:'business', entity_id: business.id, detail:'Business dibuat via Setup Wizard', created_at: nowISO()}],
    settings: {cogs:true, assets:true, tax:false, receivable:false, payable:false}
  };
}

function loadState(){
  try{
    const raw = localStorage.getItem(KEY);
    if(!raw) return null;
    return JSON.parse(raw);
  }catch{ return null; }
}
function saveState(s){ localStorage.setItem(KEY, JSON.stringify(s)); }

let state = loadState() || defaultState();
if(!loadState()) saveState(state);

// ensure business_id on all
function ensureBusinessId(){ state.transactions.forEach(t=>{ if(!t.business_id) t.business_id = state.business.id; }); saveState(state); }
ensureBusinessId();

// Helpers
function getCategory(id){ return state.categories.find(c=>c.id===id); }
function getAccount(id){ return state.accounts.find(a=>a.id===id); }
function accountBalance(acc){
  let bal = acc.opening||0;
  state.transactions.forEach(t=>{
    if(t.status==='VOIDED') return;
    if(t.type==='INCOME' && t.account_id===acc.id) bal += t.amount;
    if(t.type==='EXPENSE' && t.account_id===acc.id) bal -= t.amount;
    if(t.type==='TRANSFER'){
      if(t.from_account_id===acc.id) bal -= t.amount;
      if(t.to_account_id===acc.id) bal += t.amount;
    }
    // asset purchase handled as expense-like but via asset logic below - we treat asset as transfer to asset? Actually rule: asset purchase reduces cash, not profit
    // We model asset purchase as expense type with non-profit but account reduction is via transaction; asset creation itself also reduces account via separate logic
  });
  // also subtract asset purchases (they are cash out but not in transactions list as separate type; we handle by subtracting asset purchase_price from its account if not already via transaction)
  state.assets.forEach(a=>{
    if(a.account_id===acc.id) bal -= a.purchase_price;
  });
  return bal;
}
function computeMetrics(filterFn=null){
  const txs = state.transactions.filter(t=> t.status!=='VOIDED' && (!filterFn || filterFn(t)));
  let revenue=0, cogs=0, opex=0, cashIn=0, cashOut=0;
  txs.forEach(t=>{
    if(t.type==='INCOME'){ revenue += t.amount; cashIn += t.amount; }
    if(t.type==='EXPENSE'){
      const cat = getCategory(t.category_id);
      const isCOGS = cat && cat.classification==='COGS';
      if(isCOGS) cogs += t.amount;
      else if(cat && cat.affects_profit) opex += t.amount;
      else {
        // non-profit expense still is cash out but not opex
      }
      cashOut += t.amount;
    }
    if(t.type==='TRANSFER'){
      // no profit impact, no cashIn/Out net? But for cashflow, transfer is internal, not counted. We exclude.
    }
  });
  // asset purchases are cashOut but not expense profit - add to cashOut
  const assetCash = state.assets.reduce((s,a)=> s + (filterFn ? (filterFn({transaction_date:a.purchase_date})? a.purchase_price:0) : a.purchase_price),0);
  // Only count asset cash if within filter period - simplified: count all
  // For filtered metrics, we need to filter assets by date too
  // We'll handle filtered separately
  const gross = revenue - cogs;
  const net = gross - opex;
  const netCash = cashIn - cashOut - assetCash;
  return {revenue,cogs,opex,gross,net,cashIn,cashOut: cashOut+assetCash, netCash, count: txs.length};
}
function availableCash(){
  return state.accounts.filter(a=>!a.archived).reduce((s,a)=> s+accountBalance(a),0);
}

// Audit
function addAudit(action, entity, entity_id, detail, oldVals=null, newVals=null){
  state.audit.unshift({id:'au_'+uid(), business_id: state.business.id, user:'Owner', action, entity, entity_id, detail, old: oldVals, new: newVals, created_at: nowISO()});
  saveState(state);
}

// Render helpers
function refreshChrome(){
  document.getElementById('bizName').textContent = state.business.name + ' • ' + state.business.type;
  document.getElementById('bizType').textContent = state.accounts.filter(a=>!a.archived).map(a=>a.name).join(' • ');
  document.getElementById('bizLogo').src = state.business.logo;
  document.getElementById('countIncome').textContent = state.transactions.filter(t=>t.type==='INCOME'&&t.status!=='VOIDED').length;
  document.getElementById('countExpense').textContent = state.transactions.filter(t=>t.type==='EXPENSE'&&t.status!=='VOIDED').length;
  document.getElementById('countTransfer').textContent = state.transactions.filter(t=>t.type==='TRANSFER'&&t.status!=='VOIDED').length;
  document.getElementById('countAccounts').textContent = state.accounts.filter(a=>!a.archived).length;
  document.getElementById('countAssets').textContent = state.assets.length;
  // feature toggles
  document.getElementById('navReceivable').classList.toggle('hidden', !state.settings.receivable);
  document.getElementById('navPayable').classList.toggle('hidden', !state.settings.payable);
  // topbar setup values
  const setName = document.getElementById('setBizName'); if(setName) setName.value = state.business.name;
  const setType = document.getElementById('setBizType'); if(setType) setType.value = state.business.type;
  const setCurr = document.getElementById('setCurrency'); if(setCurr) setCurr.value = state.business.currency;
  const setTz = document.getElementById('setTimezone'); if(setTz) setTz.value = state.business.timezone;
  document.getElementById('featCOGS').checked = state.settings.cogs;
  document.getElementById('featAssets').checked = state.settings.assets;
  document.getElementById('featTax').checked = state.settings.tax;
  document.getElementById('featReceivable').checked = state.settings.receivable;
  document.getElementById('featPayable').checked = state.settings.payable;
}

let cashChart=null, reportChart=null;
function renderDashboard(range='month'){
  const m = computeMetrics();
  const avail = availableCash();
  document.getElementById('statAvailableCash').textContent = fmtIDR(avail);
  document.getElementById('statAvailableCashSub').textContent = 'dari ' + state.accounts.filter(a=>!a.archived).length + ' akun • ' + fmtIDR(state.accounts.reduce((s,a)=>s+(a.opening||0),0)) + ' opening';
  document.getElementById('statNetProfit').textContent = fmtIDR(m.net);
  document.getElementById('statNetProfitSub').textContent = 'Gross ' + fmtIDR(m.gross) + ' • Opex ' + fmtIDR(m.opex);
  document.getElementById('statReceivable').textContent = state.settings.receivable ? fmtIDR(3500000) : '—';
  document.getElementById('statPayable').textContent = state.settings.payable ? fmtIDR(2100000) : '—';
  // summary
  document.getElementById('sumRevenue').textContent = fmtIDR(m.revenue);
  document.getElementById('sumCogs').textContent = fmtIDR(m.cogs);
  document.getElementById('sumGross').textContent = fmtIDR(m.gross);
  document.getElementById('sumOpex').textContent = fmtIDR(m.opex);
  document.getElementById('sumNet').textContent = fmtIDR(m.net);
  document.getElementById('cashInVal').textContent = fmtIDR(m.cashIn);
  document.getElementById('cashOutVal').textContent = fmtIDR(m.cashOut);
  document.getElementById('cashNetVal').textContent = fmtIDR(m.netCash);
  document.getElementById('cashflowNet').textContent = 'Net: ' + fmtIDR(m.netCash);
  // mini accounts
  const mini = document.getElementById('miniAccounts');
  mini.innerHTML = state.accounts.filter(a=>!a.archived).map(a=>`
    <div class="flex items-center justify-between bg-white/10 rounded-xl px-3 py-2 border border-white/10">
      <span class="text-xs font-medium flex items-center gap-2"><span class="w-2 h-2 rounded-full ${a.type==='Cash'?'bg-emerald-400':a.type==='Bank'?'bg-sky-400':'bg-amber-400'}"></span>${a.name}</span>
      <span class="text-xs font-bold">${fmtIDR(accountBalance(a))}</span>
    </div>
  `).join('');
  // bar width for net profit positive
  const bar = document.getElementById('barNetProfit');
  const pct = Math.max(8, Math.min(100, (m.net / Math.max(1,m.revenue))*100 + 40));
  bar.style.width = pct + '%';
  bar.className = 'h-full rounded-full ' + (m.net>=0 ? 'bg-indigo-500' : 'bg-rose-500');

  // chart
  const ctx = document.getElementById('cashflowChart');
  if(ctx){
    const labels = ['Minggu 1','Minggu 2','Minggu 3','Minggu 4'];
    // distribute based on actual data: simple mock split
    const avgIn = m.cashIn/4, avgOut = (m.cashOut)/4;
    const dataIn = labels.map((_,i)=> Math.round(avgIn * (0.7 + Math.random()*0.6)));
    const dataOut = labels.map((_,i)=> Math.round(avgOut * (0.7 + Math.random()*0.6)));
    if(cashChart) cashChart.destroy();
    cashChart = new Chart(ctx, {
      type:'bar',
      data:{labels, datasets:[
        {label:'Uang Masuk', data:dataIn, backgroundColor:'#10b981', borderRadius:8, barPercentage:0.55},
        {label:'Uang Keluar', data:dataOut, backgroundColor:'#f43f5e', borderRadius:8, barPercentage:0.55}
      ]},
      options:{
        responsive:true, maintainAspectRatio:false,
        plugins:{legend:{position:'bottom', labels:{boxWidth:12, font:{size:11}}}},
        scales:{y:{ticks:{callback:(v)=> fmtNum(v)}, grid:{color:'#f1f5f9'}}, x:{grid:{display:false}}}
      }
    });
  }

  renderRecent();
}

function renderRecent(){
  const filter = document.getElementById('filterRecent').value;
  let list = [...state.transactions].sort((a,b)=> b.transaction_date.localeCompare(a.transaction_date));
  if(filter!=='all') list = list.filter(t=> t.type===filter);
  list = list.slice(0,8);
  const el = document.getElementById('recentList');
  const empty = document.getElementById('recentEmpty');
  if(list.length===0){ el.innerHTML=''; empty.classList.remove('hidden'); return; }
  empty.classList.add('hidden');
  el.innerHTML = list.map(t=>{
    const isInc = t.type==='INCOME', isExp = t.type==='EXPENSE', isTr = t.type==='TRANSFER';
    const cat = isTr ? 'Transfer' : (getCategory(t.category_id)?.name||'-');
    const acc = isTr ? `${getAccount(t.from_account_id)?.name||'?'} → ${getAccount(t.to_account_id)?.name||'?'}` : (getAccount(t.account_id)?.name||'-');
    const amt = fmtIDR(t.amount);
    const status = t.status;
    const icon = isInc ? 'arrow-down-left' : isExp ? 'arrow-up-right' : 'arrow-left-right';
    const color = isInc ? 'text-emerald-600 bg-emerald-50' : isExp ? 'text-rose-600 bg-rose-50' : 'text-sky-600 bg-sky-50';
    return `<div class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50">
      <div class="w-9 h-9 rounded-xl grid place-items-center shrink-0 ${color}"><i data-lucide="${icon}" class="w-4 h-4"></i></div>
      <div class="flex-1 min-w-0">
        <div class="text-sm font-semibold truncate">${t.description||cat} <span class="font-normal text-slate-500">• ${cat}</span></div>
        <div class="text-xs text-slate-500 truncate">${t.transaction_date} • ${acc} ${t.status==='VOIDED'? '• VOIDED':''}</div>
      </div>
      <div class="text-right shrink-0">
        <div class="text-sm font-bold ${isInc?'text-emerald-600': isExp? 'text-rose-600':''}">${isExp?'- ': isInc?'+ ':''}${amt}</div>
        <span class="inline-flex text-[10px] font-bold tracking-widest px-1.5 py-0.5 rounded ${status==='POSTED'?'bg-slate-900 text-white':'bg-slate-100 text-slate-600'}">${status}</span>
      </div>
      <button data-void="${t.id}" class="w-8 h-8 grid place-items-center rounded-lg hover:bg-slate-100 text-slate-400 ${t.status==='VOIDED'?'invisible':''}" title="Void"><i data-lucide="ban" class="w-4 h-4"></i></button>
    </div>`;
  }).join('');
  lucide.createIcons();
  el.querySelectorAll('[data-void]').forEach(b=> b.addEventListener('click', ()=> confirmVoid(b.dataset.void)));
}

function renderIncome(){
  const q = document.getElementById('searchIncome').value.toLowerCase();
  const catF = document.getElementById('filterIncomeCat').value;
  const accF = document.getElementById('filterIncomeAcc').value;
  const statF = document.getElementById('filterIncomeStatus').value;
  let list = state.transactions.filter(t=> t.type==='INCOME');
  if(q) list = list.filter(t=> (t.description||'').toLowerCase().includes(q) || (getCategory(t.category_id)?.name||'').toLowerCase().includes(q));
  if(catF) list = list.filter(t=> t.category_id===catF);
  if(accF) list = list.filter(t=> t.account_id===accF);
  if(statF) list = list.filter(t=> t.status===statF);
  list.sort((a,b)=> b.transaction_date.localeCompare(a.transaction_date));
  const tb = document.getElementById('tbodyIncome');
  const empty = document.getElementById('emptyIncome');
  if(list.length===0){ tb.innerHTML=''; empty.classList.remove('hidden'); return; }
  empty.classList.add('hidden');
  tb.innerHTML = list.map(t=>`
    <tr class="${t.status==='VOIDED'?'opacity-60 bg-slate-50':''}">
      <td class="px-4 py-3 whitespace-nowrap text-xs font-medium">${t.transaction_date}</td>
      <td class="px-4 py-3"><span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>${getCategory(t.category_id)?.name||'-'}</span></td>
      <td class="px-4 py-3 text-xs">${getAccount(t.account_id)?.name||'-'}</td>
      <td class="px-4 py-3 max-w-[260px] truncate text-xs">${t.description||'-'} ${t.party? '• '+t.party:''}</td>
      <td class="px-4 py-3 text-right font-bold text-emerald-600">+ ${fmtIDR(t.amount)}</td>
      <td class="px-4 py-3 text-center"><span class="text-[10px] font-bold tracking-widest px-2 py-1 rounded-full ${t.status==='POSTED'?'bg-slate-900 text-white':'bg-slate-200 text-slate-600'}">${t.status}</span></td>
      <td class="px-4 py-3 text-right"><div class="inline-flex gap-1">
        <button data-edit="${t.id}" class="w-7 h-7 grid place-items-center rounded-lg border border-slate-200 hover:bg-slate-50"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>
        <button data-void="${t.id}" class="w-7 h-7 grid place-items-center rounded-lg border border-slate-200 hover:bg-rose-50 text-rose-600 ${t.status==='VOIDED'?'invisible':''}"><i data-lucide="ban" class="w-3.5 h-3.5"></i></button>
      </div></td>
    </tr>
  `).join('');
  lucide.createIcons();
  tb.querySelectorAll('[data-void]').forEach(b=> b.addEventListener('click', ()=> confirmVoid(b.dataset.void)));
  tb.querySelectorAll('[data-edit]').forEach(b=> b.addEventListener('click', ()=> openEditTx(b.dataset.edit)));
}

function renderExpense(){
  const q = document.getElementById('searchExpense').value.toLowerCase();
  const catF = document.getElementById('filterExpenseCat').value;
  const accF = document.getElementById('filterExpenseAcc').value;
  const onlyProfit = document.getElementById('toggleOnlyProfit').checked;
  let list = state.transactions.filter(t=> t.type==='EXPENSE');
  if(q) list = list.filter(t=> (t.description||'').toLowerCase().includes(q) || (getCategory(t.category_id)?.name||'').toLowerCase().includes(q));
  if(catF) list = list.filter(t=> t.category_id===catF);
  if(accF) list = list.filter(t=> t.account_id===accF);
  if(onlyProfit) list = list.filter(t=> {
    const c = getCategory(t.category_id); return c && c.affects_profit;
  });
  list.sort((a,b)=> b.transaction_date.localeCompare(a.transaction_date));
  const tb = document.getElementById('tbodyExpense');
  const empty = document.getElementById('emptyExpense');
  if(list.length===0){ tb.innerHTML=''; empty.classList.remove('hidden'); return; }
  empty.classList.add('hidden');
  tb.innerHTML = list.map(t=>{
    const cat = getCategory(t.category_id);
    return `<tr class="${t.status==='VOIDED'?'opacity-60 bg-slate-50':''}">
      <td class="px-4 py-3 whitespace-nowrap text-xs font-medium">${t.transaction_date}</td>
      <td class="px-4 py-3"><span class="inline-flex text-xs font-semibold px-2 py-1 rounded-full ${cat?.classification==='COGS'?'bg-amber-50 text-amber-700 border border-amber-200':'bg-slate-100 text-slate-700'}">${cat?.name||'-'}</span></td>
      <td class="px-4 py-3 text-xs">${getAccount(t.account_id)?.name||'-'}</td>
      <td class="px-4 py-3 max-w-[260px] truncate text-xs">${t.description||'-'}</td>
      <td class="px-4 py-3 text-right font-bold text-rose-600">- ${fmtIDR(t.amount)}</td>
      <td class="px-4 py-3 text-center">${cat?.affects_profit ? '<span class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full font-bold border border-indigo-200">YES</span>' : '<span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded-full font-bold">NO</span>'}</td>
      <td class="px-4 py-3 text-right"><div class="inline-flex gap-1">
        <button data-edit="${t.id}" class="w-7 h-7 grid place-items-center rounded-lg border border-slate-200 hover:bg-slate-50"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>
        <button data-void="${t.id}" class="w-7 h-7 grid place-items-center rounded-lg border border-slate-200 hover:bg-rose-50 text-rose-600 ${t.status==='VOIDED'?'invisible':''}"><i data-lucide="ban" class="w-3.5 h-3.5"></i></button>
      </div></td>
    </tr>`;
  }).join('');
  lucide.createIcons();
  tb.querySelectorAll('[data-void]').forEach(b=> b.addEventListener('click', ()=> confirmVoid(b.dataset.void)));
  tb.querySelectorAll('[data-edit]').forEach(b=> b.addEventListener('click', ()=> openEditTx(b.dataset.edit)));
}

function renderTransfer(){
  let list = state.transactions.filter(t=> t.type==='TRANSFER').sort((a,b)=> b.transaction_date.localeCompare(a.transaction_date));
  const tb = document.getElementById('tbodyTransfer');
  const empty = document.getElementById('emptyTransfer');
  if(list.length===0){ tb.innerHTML=''; empty.classList.remove('hidden'); return; }
  empty.classList.add('hidden');
  tb.innerHTML = list.map(t=>`
    <tr class="${t.status==='VOIDED'?'opacity-60 bg-slate-50':''}">
      <td class="px-4 py-3 text-xs font-medium">${t.transaction_date}</td>
      <td class="px-4 py-3 text-xs font-semibold">${getAccount(t.from_account_id)?.name||'?'} <span class="text-slate-400">→</span> ${getAccount(t.to_account_id)?.name||'?'}</td>
      <td class="px-4 py-3 text-xs max-w-[260px] truncate">${t.description||'-'}</td>
      <td class="px-4 py-3 text-right font-bold">${fmtIDR(t.amount)}</td>
      <td class="px-4 py-3 text-center"><span class="text-[10px] font-bold tracking-widest px-2 py-1 rounded-full ${t.status==='POSTED'?'bg-sky-50 text-sky-700 border border-sky-200':'bg-slate-200 text-slate-600'}">${t.status}</span></td>
      <td class="px-4 py-3 text-right"><button data-void="${t.id}" class="w-7 h-7 grid place-items-center rounded-lg border border-slate-200 hover:bg-rose-50 text-rose-600 ${t.status==='VOIDED'?'invisible':''}"><i data-lucide="ban" class="w-3.5 h-3.5"></i></button></td>
    </tr>
  `).join('');
  lucide.createIcons();
  tb.querySelectorAll('[data-void]').forEach(b=> b.addEventListener('click', ()=> confirmVoid(b.dataset.void)));
}

function renderAccounts(){
  const grid = document.getElementById('gridAccounts');
  grid.innerHTML = state.accounts.map(a=>{
    const bal = accountBalance(a);
    const isCash = a.type==='Cash', isBank = a.type==='Bank';
    return `<div class="bg-white rounded-2xl border ${a.archived?'border-dashed opacity-60':'border-slate-200'} p-5 shadow-card">
      <div class="flex items-start justify-between">
        <div class="w-10 h-10 rounded-xl grid place-items-center ${isCash?'bg-emerald-50 text-emerald-600': isBank?'bg-sky-50 text-sky-600':'bg-amber-50 text-amber-600'}"><i data-lucide="${isCash?'banknote': isBank?'landmark':'wallet'}" class="w-5 h-5"></i></div>
        <span class="text-[10px] font-bold tracking-widest px-2 py-1 rounded-full ${a.archived?'bg-slate-100 text-slate-600':'bg-slate-900 text-white'}">${a.archived?'ARCHIVED':'ACTIVE'} • ${a.type}</span>
      </div>
      <div class="mt-4 font-bold">${a.name}</div>
      <div class="text-xs text-slate-500">Opening ${fmtIDR(a.opening||0)}</div>
      <div class="mt-3 text-[11px] tracking-widest uppercase font-semibold text-slate-400">Saldo Saat Ini</div>
      <div class="font-display text-xl font-bold">${fmtIDR(bal)}</div>
      <div class="mt-4 flex gap-2">
        <button data-edit-acc="${a.id}" class="flex-1 py-2 rounded-xl border border-slate-200 text-xs font-bold hover:bg-slate-50">Edit</button>
        <button data-archive-acc="${a.id}" class="flex-1 py-2 rounded-xl ${a.archived?'bg-slate-900 text-white':'bg-white border border-slate-200 hover:bg-slate-50'} text-xs font-bold">${a.archived?'Aktifkan':'Archive'}</button>
      </div>
    </div>`;
  }).join('');
  const tb = document.getElementById('tbodyAccounts');
  tb.innerHTML = state.accounts.map(a=>{
    const bal = accountBalance(a);
    return `<tr class="${a.archived?'opacity-60':''}">
      <td class="px-4 py-3 font-semibold text-sm">${a.name}</td>
      <td class="px-4 py-3"><span class="text-xs bg-slate-100 px-2 py-1 rounded-full font-semibold">${a.type}</span></td>
      <td class="px-4 py-3 text-right text-xs">${fmtIDR(a.opening||0)}</td>
      <td class="px-4 py-3 text-right font-bold">${fmtIDR(bal)}</td>
      <td class="px-4 py-3 text-center"><span class="text-[10px] font-bold tracking-widest px-2 py-1 rounded-full ${a.archived?'bg-amber-100 text-amber-700':'bg-emerald-50 text-emerald-700 border border-emerald-200'}">${a.archived?'ARCHIVED':'ACTIVE'}</span></td>
      <td class="px-4 py-3 text-right"><div class="inline-flex gap-1"><button data-edit-acc="${a.id}" class="w-7 h-7 grid place-items-center rounded-lg border border-slate-200"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button><button data-archive-acc="${a.id}" class="w-7 h-7 grid place-items-center rounded-lg border border-slate-200"><i data-lucide="${a.archived?'rotate-ccw':'archive'}" class="w-3.5 h-3.5"></i></button></div></td>
    </tr>`;
  }).join('');
  document.getElementById('totalAccountsBalance').textContent = 'Total: ' + fmtIDR(availableCash());
  lucide.createIcons();
  grid.querySelectorAll('[data-edit-acc]').forEach(b=> b.addEventListener('click', ()=> openAccountModal(b.dataset.editAcc)));
  grid.querySelectorAll('[data-archive-acc]').forEach(b=> b.addEventListener('click', ()=> toggleArchiveAccount(b.dataset.archiveAcc)));
  tb.querySelectorAll('[data-edit-acc]').forEach(b=> b.addEventListener('click', ()=> openAccountModal(b.dataset.editAcc)));
  tb.querySelectorAll('[data-archive-acc]').forEach(b=> b.addEventListener('click', ()=> toggleArchiveAccount(b.dataset.archiveAcc)));
}

function renderAssets(){
  const grid = document.getElementById('gridAssets');
  const totalVal = state.assets.reduce((s,a)=> s+a.purchase_price,0);
  grid.innerHTML = `
    <div class="bg-slate-900 text-white rounded-2xl p-5">
      <div class="text-xs tracking-widest uppercase opacity-70 font-semibold">Total Nilai Asset</div>
      <div class="font-display text-2xl font-bold mt-1">${fmtIDR(totalVal)}</div>
      <div class="text-xs opacity-70 mt-1">${state.assets.length} asset • tidak kurangi Net Profit</div>
    </div>
    ${state.assets.slice(0,2).map(a=>`
      <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-card">
        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 grid place-items-center"><i data-lucide="package" class="w-5 h-5"></i></div>
        <div class="font-bold mt-3">${a.name}</div>
        <div class="text-xs text-slate-500">${a.category} • ${a.purchase_date}</div>
        <div class="font-bold mt-2">${fmtIDR(a.purchase_price)}</div>
        <div class="text-xs text-slate-500">via ${getAccount(a.account_id)?.name||'-'}</div>
      </div>
    `).join('')}
  `;
  const tb = document.getElementById('tbodyAssets');
  const empty = document.getElementById('emptyAssets');
  if(state.assets.length===0){ tb.innerHTML=''; empty.classList.remove('hidden'); return; }
  empty.classList.add('hidden');
  tb.innerHTML = state.assets.map(a=>`
    <tr>
      <td class="px-4 py-3"><div class="font-semibold text-sm">${a.name}</div><div class="text-xs text-slate-500">${a.description||a.category}</div></td>
      <td class="px-4 py-3 text-xs">${getAccount(a.account_id)?.name||'-'}</td>
      <td class="px-4 py-3 text-xs">${a.purchase_date}</td>
      <td class="px-4 py-3 text-right font-bold">${fmtIDR(a.purchase_price)}</td>
      <td class="px-4 py-3 text-center"><span class="text-[10px] font-bold tracking-widest bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-1 rounded-full">${a.status}</span></td>
    </tr>
  `).join('');
  lucide.createIcons();
}

function renderReports(){
  const preset = document.getElementById('reportPreset').value;
  const typeF = document.getElementById('reportType').value;
  const catF = document.getElementById('reportCategory').value;
  const accF = document.getElementById('reportAccount').value;
  // date filter
  let from=null,to=null;
  const now = new Date();
  if(preset==='today'){ from = to = todayISO(); }
  else if(preset==='week'){ const d=new Date(); d.setDate(now.getDate()-7); from=d.toISOString().slice(0,10); to=todayISO(); }
  else if(preset==='month'){ from = now.toISOString().slice(0,7)+'-01'; to=todayISO(); }
  else if(preset==='lastMonth'){ const d=new Date(now.getFullYear(), now.getMonth()-1, 1); const e=new Date(now.getFullYear(), now.getMonth(),0); from=d.toISOString().slice(0,10); to=e.toISOString().slice(0,10); }
  else if(preset==='year'){ from = now.getFullYear()+'-01-01'; to=todayISO(); }
  else if(preset==='custom'){ from=document.getElementById('reportFrom').value||null; to=document.getElementById('reportTo').value||null; }
  // else all = no filter

  const filterFn = (t) => {
    if(from && t.transaction_date < from) return false;
    if(to && t.transaction_date > to) return false;
    if(typeF!=='all' && t.type!==typeF) return false;
    if(catF && t.category_id!==catF) return false;
    if(accF){
      if(t.type==='TRANSFER'){ if(t.from_account_id!==accF && t.to_account_id!==accF) return false; }
      else if(t.account_id!==accF) return false;
    }
    return true;
  };
  const m = computeMetrics(filterFn);
  // asset filtered
  let assetFiltered = 0;
  state.assets.forEach(a=>{
    const fake = {transaction_date: a.purchase_date};
    if(filterFn(fake)) assetFiltered += a.purchase_price;
  });
  // adjust m to include assetFiltered properly: computeMetrics already adds all assets if no filter; we recompute cashOut correction
  // For simplicity, recalc netCash with filtered assets
  const cashIn = m.cashIn, cashOutWithoutAsset = m.cashOut - state.assets.reduce((s,a)=>s+a.purchase_price,0) + assetFiltered; // hack

  document.getElementById('rptRevenue').textContent = fmtIDR(m.revenue);
  document.getElementById('rptCogs').textContent = fmtIDR(m.cogs);
  document.getElementById('rptGross').textContent = fmtIDR(m.gross);
  document.getElementById('rptOpex').textContent = fmtIDR(m.opex);
  document.getElementById('rptNet').textContent = fmtIDR(m.net);
  document.getElementById('rptCash').textContent = fmtIDR(cashIn - (m.cashOut - (state.assets.reduce((s,a)=>s+a.purchase_price,0)) ) - assetFiltered); // simplified
  // Actually properly: netCash = cashIn - (expense cashOut) - assetFiltered
  const expenseCashOut = state.transactions.filter(t=> t.status!=='VOIDED' && t.type==='EXPENSE' && filterFn(t)).reduce((s,t)=>s+t.amount,0);
  const netCash = cashIn - expenseCashOut - assetFiltered;
  document.getElementById('rptCash').textContent = fmtIDR(netCash);

  // table
  let list = state.transactions.filter(t=> t.status!=='VOIDED' && filterFn(t)).sort((a,b)=> b.transaction_date.localeCompare(a.transaction_date));
  document.getElementById('rptCount').textContent = list.length + ' transaksi';
  const tb = document.getElementById('tbodyReport');
  tb.innerHTML = list.map(t=>{
    const cat = t.type==='TRANSFER' ? 'Transfer' : (getCategory(t.category_id)?.name||'-');
    const acc = t.type==='TRANSFER' ? `${getAccount(t.from_account_id)?.name||'?'}→${getAccount(t.to_account_id)?.name||'?'}` : (getAccount(t.account_id)?.name||'-');
    return `<tr>
      <td class="px-4 py-3 text-xs">${t.transaction_date}</td>
      <td class="px-4 py-3"><span class="text-[10px] font-bold tracking-widest px-2 py-1 rounded-full ${t.type==='INCOME'?'bg-emerald-50 text-emerald-700': t.type==='EXPENSE'?'bg-rose-50 text-rose-700':'bg-sky-50 text-sky-700'}">${t.type}</span></td>
      <td class="px-4 py-3 text-xs">${cat}</td>
      <td class="px-4 py-3 text-xs">${acc}</td>
      <td class="px-4 py-3 text-right font-bold text-xs">${fmtIDR(t.amount)}</td>
      <td class="px-4 py-3 text-center"><span class="text-[10px] font-bold bg-slate-900 text-white px-2 py-1 rounded-full">${t.status}</span></td>
    </tr>`;
  }).join('') || `<tr><td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Tidak ada data untuk filter ini.</td></tr>`;

  // chart
  const ctx = document.getElementById('reportChart');
  if(ctx){
    if(reportChart) reportChart.destroy();
    const days = [...new Set(list.map(t=> t.transaction_date))].sort().slice(-7);
    const inByDay = days.map(d=> list.filter(t=> t.transaction_date===d && t.type==='INCOME').reduce((s,t)=>s+t.amount,0));
    const outByDay = days.map(d=> list.filter(t=> t.transaction_date===d && t.type==='EXPENSE').reduce((s,t)=>s+t.amount,0));
    reportChart = new Chart(ctx, {
      type:'line',
      data:{labels: days.length? days : ['—'], datasets:[
        {label:'Income', data: inByDay.length? inByDay:[0], borderColor:'#10b981', backgroundColor:'rgba(16,185,129,0.1)', tension:0.35, fill:true},
        {label:'Expense', data: outByDay.length? outByDay:[0], borderColor:'#f43f5e', backgroundColor:'rgba(244,63,94,0.08)', tension:0.35, fill:true}
      ]},
      options:{responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom'}}, scales:{y:{ticks:{callback:v=>fmtNum(v)}}, x:{grid:{display:false}}}}
    });
  }
}

function renderCategories(){
  const activeTab = document.querySelector('.cat-tab.bg-slate-900')?.dataset.catTab || 'income';
  const list = state.categories.filter(c=> (activeTab==='income'? c.type==='INCOME' : c.type==='EXPENSE'));
  const el = document.getElementById('listCategories');
  el.innerHTML = list.map(c=>`
    <div class="flex items-center gap-3 p-3 border ${c.archived?'border-dashed bg-slate-50 opacity-60':'border-slate-200 bg-white'} rounded-xl">
      <div class="w-8 h-8 rounded-lg grid place-items-center ${c.type==='INCOME'?'bg-emerald-50 text-emerald-600':'bg-slate-100 text-slate-600'}"><i data-lucide="${c.type==='INCOME'?'trending-up':'tag'}" class="w-4 h-4"></i></div>
      <div class="flex-1 min-w-0">
        <div class="text-sm font-semibold truncate">${c.name} ${c.archived?'<span class="text-[10px] bg-slate-200 px-1.5 py-0.5 rounded font-bold">ARCHIVED</span>':''}</div>
        <div class="text-xs text-slate-500">${c.classification} ${c.type==='EXPENSE'? '• ' + (c.affects_profit?'affects profit':'no profit'):''}</div>
      </div>
      <button data-archive-cat="${c.id}" class="text-xs font-bold border border-slate-200 px-3 py-1.5 rounded-full hover:bg-slate-50">${c.archived?'Aktifkan':'Archive'}</button>
      <button data-delete-cat="${c.id}" class="w-7 h-7 grid place-items-center rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
    </div>
  `).join('') || `<div class="text-sm text-slate-500 text-center py-6">Belum ada kategori.</div>`;
  lucide.createIcons();
  el.querySelectorAll('[data-archive-cat]').forEach(b=> b.addEventListener('click', ()=>{
    const cat = state.categories.find(c=>c.id===b.dataset.archiveCat);
    if(cat){ cat.archived = !cat.archived; saveState(state); addAudit(cat.archived?'ARCHIVE_CATEGORY':'ACTIVATE_CATEGORY','category',cat.id, cat.name); renderCategories(); populateSelects(); toast(cat.archived?'Kategori di-archive':'Kategori diaktifkan'); }
  }));
  el.querySelectorAll('[data-delete-cat]').forEach(b=> b.addEventListener('click', ()=>{
    const cat = state.categories.find(c=>c.id===b.dataset.deleteCat);
    if(!cat) return;
    if(state.transactions.some(t=> t.category_id===cat.id)){ toast('Kategori masih dipakai transaksi — archive saja','error'); return; }
    if(confirm(`Hapus kategori "${cat.name}"?`)){ state.categories = state.categories.filter(c=>c.id!==cat.id); saveState(state); addAudit('DELETE_CATEGORY','category',cat.id, cat.name); renderCategories(); populateSelects(); toast('Kategori dihapus'); }
  }));
}

function renderAudit(){
  const tb = document.getElementById('tbodyAudit');
  tb.innerHTML = state.audit.slice(0,50).map(a=>`
    <tr>
      <td class="px-4 py-3 text-xs whitespace-nowrap">${new Date(a.created_at).toLocaleString('id-ID')}</td>
      <td class="px-4 py-3 text-xs font-semibold">${a.user}</td>
      <td class="px-4 py-3"><span class="text-[10px] font-bold tracking-widest bg-slate-100 px-2 py-1 rounded-full">${a.action}</span></td>
      <td class="px-4 py-3 text-xs">${a.entity} <span class="text-slate-400">${a.entity_id.slice(0,6)}</span></td>
      <td class="px-4 py-3 text-xs max-w-[320px] truncate">${a.detail}</td>
    </tr>
  `).join('');
}

// Populate selects
function populateSelects(){
  const incomeCats = state.categories.filter(c=> c.type==='INCOME' && !c.archived);
  const expenseCats = state.categories.filter(c=> c.type==='EXPENSE' && !c.archived);
  const allCats = state.categories.filter(c=> !c.archived);
  const accs = state.accounts.filter(a=> !a.archived);
  // income/expense filters
  const incCatSel = document.getElementById('filterIncomeCat');
  const expCatSel = document.getElementById('filterExpenseCat');
  const repCatSel = document.getElementById('reportCategory');
  const incAccSel = document.getElementById('filterIncomeAcc');
  const expAccSel = document.getElementById('filterExpenseAcc');
  const repAccSel = document.getElementById('reportAccount');
  const txCatSel = document.getElementById('txCategory');
  const txAccSel = document.getElementById('txAccount');
  const txFromSel = document.getElementById('txFrom');
  const txToSel = document.getElementById('txTo');
  const assetAccSel = document.getElementById('assetAccount');

  const fill = (sel, opts, placeholder) => {
    if(!sel) return;
    const cur = sel.value;
    sel.innerHTML = (placeholder? `<option value="">${placeholder}</option>` : '') + opts.map(o=> `<option value="${o.id}">${o.name}</option>`).join('');
    if(cur) sel.value = cur;
  };
  fill(incCatSel, incomeCats, 'Semua Kategori');
  fill(expCatSel, expenseCats, 'Semua Kategori');
  fill(repCatSel, allCats, 'Semua');
  fill(incAccSel, accs, 'Semua Akun');
  fill(expAccSel, accs, 'Semua Akun');
  fill(repAccSel, accs, 'Semua');
  // tx category depends on tx type - will be refilled dynamically
  // but fill with all for now
  if(txCatSel) fill(txCatSel, [...incomeCats, ...expenseCats], '');
  // accounts
  const accOpts = accs.map(a=> `<option value="${a.id}">${a.name} • ${fmtIDR(accountBalance(a))}</option>`).join('');
  if(txAccSel) { const cur=txAccSel.value; txAccSel.innerHTML = accOpts; if(cur) txAccSel.value=cur; }
  if(txFromSel){ const cur=txFromSel.value; txFromSel.innerHTML = accOpts; if(cur) txFromSel.value=cur; }
  if(txToSel){ const cur=txToSel.value; txToSel.innerHTML = accOpts; if(cur) txToSel.value=cur; }
  if(assetAccSel){ const cur=assetAccSel.value; assetAccSel.innerHTML = accOpts; if(cur) assetAccSel.value=cur; }
}

function updateTxCategoryOptions(){
  const type = document.querySelector('.tx-type-btn.border-emerald-500')?.dataset.txType || 'INCOME';
  const sel = document.getElementById('txCategory');
  let opts=[];
  if(type==='INCOME') opts = state.categories.filter(c=> c.type==='INCOME' && !c.archived);
  else if(type==='EXPENSE') opts = state.categories.filter(c=> c.type==='EXPENSE' && !c.archived);
  else opts=[];
  if(type==='TRANSFER'){
    document.getElementById('groupIncomeExpense').classList.add('hidden');
    document.getElementById('groupTransfer').classList.remove('hidden');
    document.getElementById('fieldAmount').classList.remove('hidden');
  } else {
    document.getElementById('groupIncomeExpense').classList.remove('hidden');
    document.getElementById('groupTransfer').classList.add('hidden');
    sel.innerHTML = opts.map(o=> `<option value="${o.id}">${o.name} ${o.type==='EXPENSE' ? '• '+o.classification+(o.affects_profit?' • profit':' • no-profit'):''}</option>`).join('');
  }
  // profit hint
  const hint = document.getElementById('profitHint');
  if(type==='EXPENSE'){
    const cat = state.categories.find(c=>c.id===sel.value);
    if(cat){
      if(cat.affects_profit) hint.innerHTML = `Kategori <b>${cat.name}</b> akan <b>mengurangi Net Profit</b> (${cat.classification}).`;
      else hint.innerHTML = `Kategori <b>${cat.name}</b> <b>tidak mengurangi Net Profit</b> — hanya mengurangi cash (mis. Asset Purchase).`;
      hint.classList.remove('hidden');
    }
  } else if(type==='TRANSFER'){
    hint.textContent = 'Transfer tidak mempengaruhi Gross/Net Profit — hanya memindahkan saldo antar akun.';
    hint.classList.remove('hidden');
  } else {
    hint.classList.add('hidden');
  }
}

// Navigation
let currentView='dashboard';
function switchView(view){
  currentView = view;
  document.querySelectorAll('main > section').forEach(s=> s.classList.add('hidden'));
  const target = document.getElementById('view-'+view);
  if(target) target.classList.remove('hidden');
  // nav active
  document.querySelectorAll('.nav-btn').forEach(b=>{
    b.classList.remove('bg-brand-700','text-white');
    b.classList.add('text-slate-700','hover:bg-slate-50');
    if(b.dataset.view===view){
      b.classList.add('bg-brand-700','text-white');
      b.classList.remove('text-slate-700','hover:bg-slate-50');
    }
  });
  const names = {dashboard:'Dashboard', income:'Uang Masuk', expense:'Uang Keluar', transfer:'Transfer', accounts:'Accounts', assets:'Assets', reports:'Reports', settings:'Settings', audit:'Audit Trail', receivable:'Piutang', payable:'Hutang'};
  document.getElementById('breadcrumb').textContent = names[view]||view;
  // render
  if(view==='dashboard') renderDashboard();
  if(view==='income') renderIncome();
  if(view==='expense') renderExpense();
  if(view==='transfer') renderTransfer();
  if(view==='accounts') renderAccounts();
  if(view==='assets') renderAssets();
  if(view==='reports') renderReports();
  if(view==='audit') renderAudit();
  if(view==='settings') renderCategories();
  // close mobile sidebar
  document.getElementById('sidebar').classList.add('-translate-x-full');
  document.getElementById('sidebar').classList.remove('translate-x-0');
  document.getElementById('overlay').classList.add('hidden');
  window.scrollTo({top:0, behavior:'smooth'});
}

// Modals
let editingTxId = null;
function openTxModal(type='INCOME', editId=null){
  editingTxId = editId;
  document.getElementById('modalTx').classList.remove('hidden');
  // set type
  document.querySelectorAll('.tx-type-btn').forEach(b=>{
    b.classList.remove('border-emerald-500','bg-emerald-50','text-emerald-700','border-2');
    b.classList.add('border','border-slate-200','bg-white');
    if(b.dataset.txType===type){
      b.classList.add('border-emerald-500','bg-emerald-50','text-emerald-700','border-2');
      b.classList.remove('border','border-slate-200','bg-white');
    }
  });
  updateTxCategoryOptions();
  const title = document.getElementById('txModalTitle');
  const sub = document.getElementById('txModalSub');
  if(editId){
    const t = state.transactions.find(x=>x.id===editId);
    if(t){
      title.textContent = 'Edit Transaksi';
      sub.textContent = t.type==='INCOME' ? 'Edit Uang Masuk' : t.type==='EXPENSE' ? 'Edit Uang Keluar' : 'Edit Transfer';
      document.getElementById('txAmount').value = fmtNum(t.amount);
      document.getElementById('txDate').value = t.transaction_date;
      document.getElementById('txDesc').value = t.description||'';
      document.getElementById('txParty').value = t.party||'';
      document.getElementById('txRef').value = t.reference||'';
      if(t.type==='TRANSFER'){
        document.getElementById('txFrom').value = t.from_account_id;
        document.getElementById('txTo').value = t.to_account_id;
      } else {
        document.getElementById('txCategory').value = t.category_id;
        document.getElementById('txAccount').value = t.account_id;
      }
    }
  } else {
    title.textContent = type==='INCOME' ? 'Tambah Uang Masuk' : type==='EXPENSE' ? 'Tambah Uang Keluar' : 'Transfer Antar Akun';
    sub.textContent = 'Isi form di bawah — target <30 detik';
    document.getElementById('formTx').reset();
    document.getElementById('txDate').value = todayISO();
    document.getElementById('txAmount').value = '';
  }
  lucide.createIcons();
  setTimeout(()=> document.getElementById('txAmount').focus(), 50);
}
function closeTxModal(){ document.getElementById('modalTx').classList.add('hidden'); editingTxId=null; }
function openEditTx(id){
  const t = state.transactions.find(x=>x.id===id);
  if(!t) return;
  if(t.status==='VOIDED'){ toast('Transaksi VOIDED tidak bisa di-edit','error'); return; }
  openTxModal(t.type, id);
}

function openAccountModal(id=null){
  document.getElementById('modalAccount').classList.remove('hidden');
  if(id){
    const a = state.accounts.find(x=>x.id===id);
    document.getElementById('accModalTitle').textContent='Edit Account';
    document.getElementById('accId').value=a.id;
    document.getElementById('accName').value=a.name;
    document.getElementById('accType').value=a.type;
    document.getElementById('accOpening').value= a.opening? fmtNum(a.opening):'';
  } else {
    document.getElementById('accModalTitle').textContent='Tambah Account';
    document.getElementById('formAccount').reset();
    document.getElementById('accId').value='';
  }
}
function closeAccountModal(){ document.getElementById('modalAccount').classList.add('hidden'); }
function toggleArchiveAccount(id){
  const a = state.accounts.find(x=>x.id===id);
  if(!a) return;
  const willArchive = !a.archived;
  if(willArchive && state.accounts.filter(x=>!x.archived).length===1){
    toast('Minimal 1 akun aktif harus tersedia','error'); return;
  }
  // confirm
  showConfirm(
    willArchive? 'Archive account?' : 'Aktifkan account?',
    willArchive? `Account "${a.name}" akan di-archive. Saldo tetap dihitung tapi disembunyikan dari pilihan transaksi.` : `Account "${a.name}" akan diaktifkan kembali.`,
    willArchive?'Archive':'Aktifkan',
    ()=>{
      a.archived = !a.archived;
      saveState(state);
      addAudit(a.archived?'ARCHIVE_ACCOUNT':'ACTIVATE_ACCOUNT','account',a.id, a.name);
      renderAccounts(); renderDashboard(); populateSelects(); refreshChrome(); toast(a.archived?'Account di-archive':'Account diaktifkan');
    }
  );
}

function openAssetModal(){ document.getElementById('modalAsset').classList.remove('hidden'); document.getElementById('assetDate').value=todayISO(); }
function closeAssetModal(){ document.getElementById('modalAsset').classList.add('hidden'); }

function showConfirm(title, desc, okLabel, onOk){
  document.getElementById('confirmTitle').textContent=title;
  document.getElementById('confirmDesc').textContent=desc;
  document.getElementById('btnConfirmOk').textContent=okLabel;
  document.getElementById('modalConfirm').classList.remove('hidden');
  const btn = document.getElementById('btnConfirmOk');
  const handler = ()=>{
    btn.removeEventListener('click', handler);
    document.getElementById('modalConfirm').classList.add('hidden');
    onOk();
  };
  btn.addEventListener('click', handler);
  lucide.createIcons();
}
function closeConfirm(){ document.getElementById('modalConfirm').classList.add('hidden'); }

function confirmVoid(id){
  const t = state.transactions.find(x=>x.id===id);
  if(!t || t.status==='VOIDED') return;
  showConfirm('Void transaksi?', `Transaksi ${t.type} ${fmtIDR(t.amount)} akan di-VOID. Dampak saldo & laporan akan di-reverse. Audit trail tercatat.`, 'Ya, Void', ()=>{
    const old = {...t};
    t.status='VOIDED';
    saveState(state);
    addAudit('VOID_TRANSACTION','transaction',t.id, `${t.type} ${fmtIDR(t.amount)} • ${t.description||''}`, old, {...t});
    refreshChrome(); renderDashboard(); renderIncome(); renderExpense(); renderTransfer(); renderReports(); toast('Transaksi di-void • saldo & laporan ter-reverse');
  });
}

function toast(msg, type='success'){
  const el = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  el.classList.remove('hidden');
  el.firstElementChild.className = 'text-sm font-medium px-4 py-3 rounded-xl shadow-float flex items-center gap-3 ' + (type==='error'?'bg-rose-600 text-white':'bg-slate-900 text-white');
  setTimeout(()=> el.classList.add('hidden'), 2400);
}

// Theme
function updateThemeUI(isDark){
  document.querySelectorAll('.icon-moon').forEach(el=> el.classList.toggle('hidden', isDark));
  document.querySelectorAll('.icon-sun').forEach(el=> el.classList.toggle('hidden', !isDark));
  document.querySelectorAll('.theme-label').forEach(el=> el.textContent = isDark ? 'Mode Gelap' : 'Mode Terang');
  const knob = document.querySelector('.theme-knob');
  if(knob) knob.style.transform = isDark ? 'translateX(20px)' : 'translateX(0)';
  const sw = document.getElementById('btnThemeToggleSidebar');
  if(sw){
    sw.classList.toggle('bg-slate-700', isDark);
    sw.classList.toggle('bg-slate-200', !isDark);
  }
  if(window.lucide) lucide.createIcons();
}
function applyTheme(isDark){
  document.documentElement.classList.toggle('dark', isDark);
  try{ localStorage.setItem('keukita_theme', isDark ? 'dark' : 'light'); }catch(e){}
  updateThemeUI(isDark);
  // update chart grids for dark
  try{
    if(cashChart){
      cashChart.options.scales.y.grid.color = isDark ? '#1e293b' : '#f1f5f9';
      cashChart.options.scales.x.grid.color = isDark ? '#1e293b' : '#f1f5f9';
      cashChart.update();
    }
    if(reportChart){
      reportChart.options.scales.y.grid.color = isDark ? '#1e293b' : '#f1f5f9';
      reportChart.update();
    }
  }catch(e){}
}

// Init
document.addEventListener('DOMContentLoaded', ()=>{
  // theme init UI
  const initialDark = document.documentElement.classList.contains('dark');
  updateThemeUI(initialDark);
  // icons
  if(window.lucide) lucide.createIcons();
  refreshChrome();
  populateSelects();
  renderDashboard();
  renderCategories();
  // nav
  document.querySelectorAll('.nav-btn').forEach(b=> b.addEventListener('click', ()=> switchView(b.dataset.view)));
  document.querySelectorAll('[data-view-jump]').forEach(b=> b.addEventListener('click', ()=> switchView(b.dataset.viewJump)));
  document.querySelectorAll('[data-quick]').forEach(b=> b.addEventListener('click', ()=>{
    const t = b.dataset.quick;
    if(t==='income') openTxModal('INCOME');
    if(t==='expense') openTxModal('EXPENSE');
    if(t==='transfer') openTxModal('TRANSFER');
  }));
  // sidebar mobile
  document.getElementById('btnOpenSidebar').addEventListener('click', ()=>{
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebar').classList.add('translate-x-0');
    document.getElementById('overlay').classList.remove('hidden');
  });
  document.getElementById('btnCloseSidebar').addEventListener('click', ()=>{
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('overlay').classList.add('hidden');
  });
  document.getElementById('overlay').addEventListener('click', ()=>{
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('overlay').classList.add('hidden');
  });
  // theme toggles
  const toggleTheme = ()=>{
    const isDark = !document.documentElement.classList.contains('dark');
    applyTheme(isDark);
  };
  document.getElementById('btnThemeToggle')?.addEventListener('click', toggleTheme);
  document.getElementById('btnThemeToggleSidebar')?.addEventListener('click', toggleTheme);
  // listen system preference changes when no manual override? optional
  try{
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e)=>{
      const saved = localStorage.getItem('keukita_theme');
      if(!saved) applyTheme(e.matches);
    });
  }catch(e){}
  // tx type picker
  document.querySelectorAll('.tx-type-btn').forEach(b=> b.addEventListener('click', ()=>{
    document.querySelectorAll('.tx-type-btn').forEach(x=>{ x.classList.remove('border-emerald-500','bg-emerald-50','text-emerald-700','border-2'); x.classList.add('border','border-slate-200','bg-white'); });
    b.classList.add('border-emerald-500','bg-emerald-50','text-emerald-700','border-2');
    b.classList.remove('border','border-slate-200','bg-white');
    updateTxCategoryOptions();
    // update title
    const t = b.dataset.txType;
    document.getElementById('txModalTitle').textContent = t==='INCOME'?'Tambah Uang Masuk': t==='EXPENSE'?'Tambah Uang Keluar':'Transfer Antar Akun';
  }));
  document.getElementById('txCategory').addEventListener('change', updateTxCategoryOptions);
  document.getElementById('txAmount').addEventListener('input', (e)=> formatInputAmount(e.target));
  document.getElementById('accOpening').addEventListener('input', (e)=> formatInputAmount(e.target));
  document.getElementById('assetPrice').addEventListener('input', (e)=> formatInputAmount(e.target));

  // form tx submit
  document.getElementById('formTx').addEventListener('submit', (e)=>{
    e.preventDefault();
    const type = document.querySelector('.tx-type-btn.border-emerald-500')?.dataset.txType || 'INCOME';
    const amount = parseAmount(document.getElementById('txAmount').value);
    if(!amount || amount<=0){ document.getElementById('errAmount').classList.remove('hidden'); return; }
    document.getElementById('errAmount').classList.add('hidden');
    const date = document.getElementById('txDate').value || todayISO();
    const desc = document.getElementById('txDesc').value.trim();
    const party = document.getElementById('txParty').value.trim();
    const ref = document.getElementById('txRef').value.trim();
    if(type==='TRANSFER'){
      const from = document.getElementById('txFrom').value;
      const to = document.getElementById('txTo').value;
      if(!from || !to){ toast('Pilih akun asal & tujuan','error'); return; }
      if(from===to){ toast('Akun asal & tujuan tidak boleh sama','error'); return; }
      if(editingTxId){
        const t = state.transactions.find(x=>x.id===editingTxId);
        const old={...t};
        Object.assign(t, {from_account_id:from, to_account_id:to, amount, transaction_date:date, description:desc, reference:ref, party});
        addAudit('UPDATE_TRANSACTION','transaction',t.id, `Transfer ${fmtIDR(amount)}`, old, {...t});
        saveState(state); closeTxModal(); refreshChrome(); renderDashboard(); renderTransfer(); renderReports(); toast('Transfer diperbarui');
      } else {
        const nt = {id:'tx_'+uid(), business_id: state.business.id, type:'TRANSFER', status:'POSTED', from_account_id:from, to_account_id:to, amount, transaction_date:date, description:desc, reference:ref, party, created_at: nowISO()};
        state.transactions.unshift(nt);
        saveState(state); addAudit('CREATE_TRANSACTION','transaction',nt.id, `Transfer ${fmtIDR(amount)}`); closeTxModal(); refreshChrome(); renderDashboard(); renderTransfer(); renderReports(); toast('Transfer disimpan • profit tidak berubah');
      }
    } else {
      const cat = document.getElementById('txCategory').value;
      const acc = document.getElementById('txAccount').value;
      if(!cat || !acc){ toast('Pilih kategori & akun','error'); return; }
      if(editingTxId){
        const t = state.transactions.find(x=>x.id===editingTxId);
        const old={...t};
        Object.assign(t, {category_id:cat, account_id:acc, amount, transaction_date:date, description:desc, reference:ref, party});
        addAudit('UPDATE_TRANSACTION','transaction',t.id, `${t.type} ${fmtIDR(amount)}`, old, {...t});
        saveState(state); closeTxModal(); refreshChrome(); renderDashboard(); renderIncome(); renderExpense(); renderReports(); toast('Transaksi diperbarui');
      } else {
        const nt = {id:'tx_'+uid(), business_id: state.business.id, type, status:'POSTED', category_id:cat, account_id:acc, amount, transaction_date:date, description:desc, reference:ref, party, created_at: nowISO()};
        state.transactions.unshift(nt);
        const catName = getCategory(cat)?.name||'';
        saveState(state); addAudit('CREATE_TRANSACTION','transaction',nt.id, `${type} ${fmtIDR(amount)} • ${catName}`); closeTxModal(); refreshChrome(); renderDashboard(); if(type==='INCOME') renderIncome(); else renderExpense(); renderReports(); toast(type==='INCOME' ? 'Uang masuk disimpan • saldo & revenue +'+fmtIDR(amount) : 'Uang keluar disimpan • saldo -'+fmtIDR(amount));
      }
    }
  });

  // close tx modal
  document.querySelectorAll('[data-close-tx]').forEach(el=> el.addEventListener('click', closeTxModal));
  // account modal
  document.getElementById('btnAddAccount').addEventListener('click', ()=> openAccountModal());
  document.querySelectorAll('[data-close-acc]').forEach(el=> el.addEventListener('click', closeAccountModal));
  document.getElementById('formAccount').addEventListener('submit', (e)=>{
    e.preventDefault();
    const id = document.getElementById('accId').value;
    const name = document.getElementById('accName').value.trim();
    const type = document.getElementById('accType').value;
    const opening = parseAmount(document.getElementById('accOpening').value);
    if(!name){ toast('Nama akun wajib','error'); return; }
    if(id){
      const a = state.accounts.find(x=>x.id===id);
      const old={...a};
      a.name=name; a.type=type; a.opening=opening;
      saveState(state); addAudit('UPDATE_ACCOUNT','account',a.id, name, old, {...a}); closeAccountModal(); renderAccounts(); renderDashboard(); populateSelects(); refreshChrome(); toast('Account diperbarui');
    } else {
      const na = {id:'acc_'+uid(), name, type, opening, archived:false};
      state.accounts.push(na);
      saveState(state); addAudit('CREATE_ACCOUNT','account',na.id, name); closeAccountModal(); renderAccounts(); renderDashboard(); populateSelects(); refreshChrome(); toast('Account ditambah • opening tidak hitung revenue');
    }
  });
  // asset
  document.getElementById('btnAddAsset').addEventListener('click', openAssetModal);
  document.querySelectorAll('[data-close-asset]').forEach(el=> el.addEventListener('click', closeAssetModal));
  document.getElementById('formAsset').addEventListener('submit', (e)=>{
    e.preventDefault();
    const name=document.getElementById('assetName').value.trim();
    const price=parseAmount(document.getElementById('assetPrice').value);
    const acc=document.getElementById('assetAccount').value;
    const date=document.getElementById('assetDate').value||todayISO();
    const desc=document.getElementById('assetDesc').value.trim();
    if(!name || !price) { toast('Nama & harga wajib','error'); return; }
    if(!acc){ toast('Pilih akun pembayaran','error'); return; }
    const bal = accountBalance(getAccount(acc));
    if(price > bal) { if(!confirm(`Saldo ${getAccount(acc).name} ${fmtIDR(bal)} kurang dari harga ${fmtIDR(price)}. Tetap lanjut?`)) return; }
    const na={id:'as_'+uid(), name, category:'Asset', purchase_date:date, purchase_price:price, account_id:acc, description:desc, status:'ACTIVE'};
    state.assets.unshift(na);
    saveState(state); addAudit('CREATE_ASSET','asset',na.id, `${name} ${fmtIDR(price)}`); closeAssetModal(); renderAssets(); renderAccounts(); renderDashboard(); populateSelects(); refreshChrome(); toast('Asset dicatat • cash -'+fmtIDR(price)+' • profit tidak berkurang');
  });

  // category
  document.getElementById('btnAddCategory').addEventListener('click', ()=> document.getElementById('modalCategory').classList.remove('hidden'));
  document.querySelectorAll('[data-close-cat]').forEach(el=> el.addEventListener('click', ()=> document.getElementById('modalCategory').classList.add('hidden')));
  document.getElementById('catType').addEventListener('change', (e)=>{
    const isExp = e.target.value==='EXPENSE';
    document.getElementById('wrapClassification').classList.toggle('hidden', !isExp);
    document.getElementById('wrapAffects').classList.toggle('hidden', !isExp);
  });
  document.getElementById('formCategory').addEventListener('submit', (e)=>{
    e.preventDefault();
    const name=document.getElementById('catName').value.trim();
    const type=document.getElementById('catType').value;
    const cls=document.getElementById('catClass').value;
    const affects=document.getElementById('catAffects').checked;
    if(!name){ toast('Nama kategori wajib','error'); return; }
    if(state.categories.some(c=> c.name.toLowerCase()===name.toLowerCase() && c.type===type)){ toast('Kategori sudah ada','error'); return; }
    const nc={id:'cat_'+uid(), name, type, classification: type==='INCOME'? 'Sales':cls, affects_profit: type==='INCOME'? true: affects, archived:false};
    state.categories.push(nc);
    saveState(state); addAudit('CREATE_CATEGORY','category',nc.id, name); document.getElementById('modalCategory').classList.add('hidden'); document.getElementById('formCategory').reset(); renderCategories(); populateSelects(); toast('Kategori ditambah');
  });
  document.querySelectorAll('.cat-tab').forEach(b=> b.addEventListener('click', ()=>{
    document.querySelectorAll('.cat-tab').forEach(x=>{ x.classList.remove('bg-slate-900','text-white'); x.classList.add('bg-slate-100'); });
    b.classList.add('bg-slate-900','text-white'); b.classList.remove('bg-slate-100'); renderCategories();
  }));

  // confirm close
  document.querySelectorAll('[data-close-confirm]').forEach(el=> el.addEventListener('click', closeConfirm));

  // settings saves
  document.getElementById('btnSaveBusiness').addEventListener('click', ()=>{
    const n=document.getElementById('setBizName').value.trim();
    const t=document.getElementById('setBizType').value;
    const c=document.getElementById('setCurrency').value;
    const tz=document.getElementById('setTimezone').value;
    if(!n){ toast('Nama bisnis wajib','error'); return; }
    const old={...state.business};
    state.business.name=n; state.business.type=t; state.business.currency=c; state.business.timezone=tz;
    saveState(state); addAudit('UPDATE_BUSINESS','business',state.business.id, n, old, {...state.business}); refreshChrome(); toast('Bisnis diperbarui');
  });
  ['featCOGS','featAssets','featTax','featReceivable','featPayable'].forEach(id=>{
    document.getElementById(id).addEventListener('change', (e)=>{
      const key = id.replace('feat','').toLowerCase();
      const map={cogs:'cogs', assets:'assets', tax:'tax', receivable:'receivable', payable:'payable'};
      state.settings[map[key]] = e.target.checked;
      saveState(state); refreshChrome(); toast('Pengaturan disimpan'); if(key==='assets' && !e.target.checked) toast('Menu Assets tetap tampil tapi bisa disembunyikan (progressive disclosure)');
    });
  });

  // reports
  document.getElementById('reportPreset').addEventListener('change', (e)=>{
    document.getElementById('reportCustomRange').classList.toggle('hidden', e.target.value!=='custom');
  });
  document.getElementById('btnApplyReport').addEventListener('click', renderReports);
  document.getElementById('reportType').addEventListener('change', renderReports);
  document.getElementById('reportCategory').addEventListener('change', renderReports);
  document.getElementById('reportAccount').addEventListener('change', renderReports);
  document.getElementById('btnExportExcel').addEventListener('click', ()=> toast('Export Excel (mock) — mengikuti filter aktif ✓'));
  document.getElementById('btnExportPdf').addEventListener('click', ()=> toast('Export PDF (mock) — mengikuti filter aktif ✓'));
  document.getElementById('btnExportDash').addEventListener('click', ()=> toast('Export Dashboard (mock) ✓'));

  // filters
  document.getElementById('searchIncome').addEventListener('input', renderIncome);
  document.getElementById('filterIncomeCat').addEventListener('change', renderIncome);
  document.getElementById('filterIncomeAcc').addEventListener('change', renderIncome);
  document.getElementById('filterIncomeStatus').addEventListener('change', renderIncome);
  document.getElementById('searchExpense').addEventListener('input', renderExpense);
  document.getElementById('filterExpenseCat').addEventListener('change', renderExpense);
  document.getElementById('filterExpenseAcc').addEventListener('change', renderExpense);
  document.getElementById('toggleOnlyProfit').addEventListener('change', renderExpense);
  document.getElementById('filterRecent').addEventListener('change', renderRecent);
  document.getElementById('globalSearch').addEventListener('input', (e)=>{
    const q=e.target.value.toLowerCase();
    if(!q){ renderRecent(); return; }
    // quick filter recent
    const list = state.transactions.filter(t=> (t.description||'').toLowerCase().includes(q) || (getCategory(t.category_id)?.name||'').toLowerCase().includes(q));
    const el=document.getElementById('recentList');
    if(list.length===0) el.innerHTML=`<div class="p-8 text-center text-sm text-slate-500">Tidak ada hasil untuk "${q}"</div>`;
    else {
      el.innerHTML = list.slice(0,6).map(t=>{
        const cat=getCategory(t.category_id)?.name||'Transfer';
        return `<div class="px-5 py-3 flex justify-between items-center hover:bg-slate-50"><span class="text-sm font-medium">${cat} • ${t.description||''}</span><span class="font-bold text-sm">${fmtIDR(t.amount)}</span></div>`;
      }).join('');
    }
  });
  // range buttons (mock)
  document.querySelectorAll('.range-btn').forEach(b=> b.addEventListener('click', ()=>{
    document.querySelectorAll('.range-btn').forEach(x=>{ x.classList.remove('bg-slate-900','text-white'); x.classList.add('text-slate-600'); });
    b.classList.add('bg-slate-900','text-white'); b.classList.remove('text-slate-600');
    renderDashboard(b.dataset.range);
  }));

  // reset
  document.getElementById('btnResetDemo').addEventListener('click', ()=>{
    showConfirm('Reset data demo?','Semua transaksi, akun, dan audit akan dikembalikan ke default Coffee Shop.', 'Reset', ()=>{
      localStorage.removeItem(KEY);
      state = defaultState(); saveState(state); refreshChrome(); populateSelects(); switchView('dashboard'); renderDashboard(); toast('Data di-reset ke default');
    });
  });
  document.getElementById('btnDangerReset').addEventListener('click', ()=>{
    document.getElementById('btnResetDemo').click();
  });
  document.getElementById('btnLogout').addEventListener('click', ()=> toast('Logout (mock) — session akan di-rotate di Laravel (HttpOnly, SameSite)'));
  // init renders
  renderIncome(); renderExpense(); renderTransfer(); renderAccounts(); renderAssets(); renderReports();
  // keyboard shortcut "/"
  document.addEventListener('keydown', (e)=>{
    if(e.key==='/' && document.activeElement.tagName!=='INPUT' && document.activeElement.tagName!=='SELECT'){
      e.preventDefault(); document.getElementById('globalSearch').focus();
    }
    if(e.key==='Escape'){ closeTxModal(); closeAccountModal(); closeAssetModal(); closeConfirm(); document.getElementById('modalCategory').classList.add('hidden'); }
  });
});
