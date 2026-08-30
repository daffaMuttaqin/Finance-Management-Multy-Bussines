/* Setup Wizard Logic */
const KEY='keukita_v2';
const TEMPLATES={
  "Coffee Shop":{income:["Coffee Sales","Food Sales","Other Sales"], expense:["Raw Material","Salary","Rent","Electricity","Marketing","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:2000000},{name:"Bank BCA",type:"Bank",opening:8000000},{name:"QRIS",type:"E-Wallet",opening:1500000}]},
  "Bakery / Patisserie":{income:["Cake Sales","Dessert Sales","Catering","Other Sales"], expense:["Ingredients","Packaging","Salary","Rent","Marketing","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:1500000},{name:"Bank BCA",type:"Bank",opening:10000000},{name:"QRIS",type:"E-Wallet",opening:1200000}]},
  "Travel":{income:["Tour","Ticket","Transportation","Other Income"], expense:["Ticket Cost","Hotel","Transportation","Commission","Marketing","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:3000000},{name:"Bank BCA",type:"Bank",opening:12000000},{name:"E-Wallet",type:"E-Wallet",opening:2000000}]},
  "Retail":{income:["Product Sales","Other Sales"], expense:["Product Cost","Salary","Rent","Utilities","Marketing","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:2500000},{name:"Bank",type:"Bank",opening:9000000},{name:"QRIS",type:"E-Wallet",opening:1000000}]},
  "Services":{income:["Service Revenue","Other Income"], expense:["Operational","Salary","Software","Marketing","Rent","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:2000000},{name:"Bank",type:"Bank",opening:7000000},{name:"E-Wallet",type:"E-Wallet",opening:800000}]},
  "Other":{income:["Sales","Other Income"], expense:["COGS","Operational","Marketing","Salary","Rent","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:2000000},{name:"Bank",type:"Bank",opening:5000000}]},
  "Restaurant":{income:["Food Sales","Beverage Sales","Other Sales"], expense:["Ingredients","Salary","Rent","Utilities","Marketing","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:3000000},{name:"Bank",type:"Bank",opening:10000000},{name:"QRIS",type:"E-Wallet",opening:2000000}]},
  "Catering":{income:["Catering Sales","Other Income"], expense:["Ingredients","Packaging","Transport","Salary","Marketing","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:2000000},{name:"Bank",type:"Bank",opening:8000000},{name:"QRIS",type:"E-Wallet",opening:1000000}]},
  "Salon / Barbershop":{income:["Service Revenue","Product Sales","Other Income"], expense:["Product Cost","Salary","Rent","Utilities","Marketing","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:1500000},{name:"Bank",type:"Bank",opening:6000000},{name:"QRIS",type:"E-Wallet",opening:800000}]},
  "Laundry":{income:["Laundry Service","Other Income"], expense:["Detergent","Utilities","Salary","Rent","Marketing","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:1000000},{name:"Bank",type:"Bank",opening:5000000}]},
  "Online Shop":{income:["Product Sales","Shipping Income","Other Income"], expense:["Product Cost","Shipping Cost","Ads","Packaging","Other Expense"], accounts:[{name:"Cash",type:"Cash",opening:1500000},{name:"Bank",type:"Bank",opening:7000000},{name:"E-Wallet",type:"E-Wallet",opening:1200000}]},
};
const fmtNum=n=> new Intl.NumberFormat('id-ID').format(Math.round(n||0));
const fmtIDR=n=> new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(Math.round(n||0));
const parseAmount=s=> parseInt(String(s||'').replace(/[^0-9]/g,'')||'0',10);
const uid=()=> Math.random().toString(36).slice(2,9);

let step=1;
let wAccounts=[], wCategories=[];

function loadTemplate(type){
  const t=TEMPLATES[type]||TEMPLATES["Other"];
  wAccounts = t.accounts.map(a=> ({id:'acc_'+uid(), name:a.name, type:a.type, opening:a.opening}));
  wCategories = [
    ...t.income.map(n=> ({id:'cat_'+uid(), name:n, type:'INCOME', classification:'Sales', affects:true})),
    ...t.expense.map(n=> ({id:'cat_'+uid(), name:n, type:'EXPENSE', classification: n==='Raw Material'||n==='Ingredients'||n==='Product Cost'||n==='COGS'?'COGS':'Operational', affects:true})),
  ];
}

function renderStep(){
  for(let i=1;i<=5;i++) document.getElementById('panel-'+i).classList.toggle('hidden', i!==step);
  document.getElementById('wizFooter').classList.toggle('hidden', step===5);
  document.getElementById('stepLabel').textContent=`Step ${step}/5`;
  const titles=["Business Information","Accounts","Categories","Financial Settings","Finish"];
  document.getElementById('stepTitle').textContent=titles[step-1];
  document.getElementById('stepBar').style.width=(step/5*100)+'%';
  // dots
  const dots=document.getElementById('stepDots');
  dots.innerHTML=[1,2,3,4,5].map(i=> `<div class="flex items-center gap-2"><div class="w-7 h-7 rounded-full grid place-items-center text-xs font-bold ${i<step?'bg-emerald-600 text-white': i===step?'bg-slate-900 text-white':'bg-slate-100 text-slate-500 border'}">${i<step?'✓':i}</div>${i<5?`<div class="w-8 h-0.5 ${i<step?'bg-emerald-600':'bg-slate-200'} hidden sm:block"></div>`:''}</div>`).join('');
  document.getElementById('btnPrev').disabled = step===1;
  document.getElementById('btnNext').textContent = step===4? 'Lanjut ke Finish →' : 'Lanjut →';
  if(step===2) renderWAccounts();
  if(step===3) renderWCategories();
  if(step===5) renderFinish();
  if(window.lucide) lucide.createIcons();
}

function renderTemplatePreview(){
  const type=document.getElementById('wType').value;
  const t=TEMPLATES[type]||TEMPLATES["Other"];
  document.getElementById('templatePreview').innerHTML=`
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div><div class="font-semibold text-xs uppercase tracking-widest text-slate-500">Income</div><div class="mt-1 flex flex-wrap gap-1">${t.income.map(n=>`<span class="text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-1 rounded-full">${n}</span>`).join('')}</div></div>
      <div><div class="font-semibold text-xs uppercase tracking-widest text-slate-500">Expense</div><div class="mt-1 flex flex-wrap gap-1">${t.expense.map(n=>`<span class="text-xs bg-slate-100 px-2 py-1 rounded-full">${n}</span>`).join('')}</div></div>
      <div><div class="font-semibold text-xs uppercase tracking-widest text-slate-500">Accounts</div><div class="mt-1 flex flex-wrap gap-1">${t.accounts.map(a=>`<span class="text-xs bg-sky-50 text-sky-700 border border-sky-200 px-2 py-1 rounded-full">${a.name} • ${fmtIDR(a.opening)}</span>`).join('')}</div></div>
    </div>`;
}

function renderWAccounts(){
  const el=document.getElementById('wAccounts');
  el.innerHTML=wAccounts.map(a=>`
    <div class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl bg-slate-50">
      <div class="w-8 h-8 rounded-lg bg-white border grid place-items-center text-xs font-bold">${a.type[0]}</div>
      <div class="flex-1 min-w-0"><input value="${a.name}" data-edit-acc="${a.id}" data-field="name" class="bg-transparent font-semibold text-sm outline-none w-full"/><div class="text-xs text-slate-500">${a.type} • Opening ${fmtIDR(a.opening)}</div></div>
      <input value="${a.opening?fmtNum(a.opening):''}" data-edit-acc="${a.id}" data-field="opening" placeholder="0" class="w-28 border border-slate-200 rounded-lg px-2 py-1.5 text-sm bg-white"/>
      <button data-del-acc="${a.id}" class="w-7 h-7 grid place-items-center rounded-lg hover:bg-white border border-transparent hover:border-slate-200"><i data-lucide="trash-2" class="w-4 h-4 text-slate-500"></i></button>
    </div>
  `).join('');
  el.querySelectorAll('[data-edit-acc]').forEach(inp=>{
    inp.addEventListener('change', (e)=>{
      const id=e.target.dataset.editAcc, field=e.target.dataset.field;
      const acc=wAccounts.find(x=>x.id===id);
      if(field==='name') acc.name=e.target.value;
      if(field==='opening') acc.opening=parseAmount(e.target.value);
    });
    inp.addEventListener('input', (e)=>{
      if(e.target.dataset.field==='opening'){
        // live format? keep simple
      }
    });
  });
  el.querySelectorAll('[data-del-acc]').forEach(b=> b.addEventListener('click', ()=>{
    if(wAccounts.length===1){ alert('Minimal 1 akun'); return; }
    wAccounts=wAccounts.filter(x=>x.id!==b.dataset.delAcc); renderWAccounts();
  }));
  lucide.createIcons();
}

function renderWCategories(){
  const tab=document.querySelector('.wcat-tab.bg-slate-900')?.dataset.wcat||'income';
  const list=wCategories.filter(c=> c.type===tab.toUpperCase());
  document.getElementById('wCatCount').textContent=list.length+' kategori';
  document.getElementById('wCategories').innerHTML=list.map(c=>`
    <div class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl ${tab==='income'?'bg-emerald-50/50':'bg-white'}">
      <div class="flex-1 min-w-0"><input value="${c.name}" data-edit-cat="${c.id}" class="bg-transparent font-semibold text-sm outline-none w-full"/><div class="text-xs text-slate-500">${c.classification} ${c.type==='EXPENSE'&&!c.affects?'• no-profit':''}</div></div>
      ${c.type==='EXPENSE'?`<label class="flex items-center gap-1 text-xs"><input type="checkbox" ${c.affects?'checked':''} data-toggle-cat="${c.id}"/> profit</label>`:''}
      <button data-del-cat="${c.id}" class="w-7 h-7 grid place-items-center rounded-lg hover:bg-white border"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
    </div>
  `).join('') || `<div class="text-sm text-slate-500 text-center py-6">Belum ada kategori ${tab}</div>`;
  document.getElementById('wCategories').querySelectorAll('[data-edit-cat]').forEach(inp=> inp.addEventListener('change', e=>{
    const cat=wCategories.find(x=>x.id===e.target.dataset.editCat); if(cat) cat.name=e.target.value;
  }));
  document.getElementById('wCategories').querySelectorAll('[data-toggle-cat]').forEach(cb=> cb.addEventListener('change', e=>{
    const cat=wCategories.find(x=>x.id===e.target.dataset.toggleCat); if(cat) cat.affects=e.target.checked;
  }));
  document.getElementById('wCategories').querySelectorAll('[data-del-cat]').forEach(b=> b.addEventListener('click', ()=>{
    wCategories=wCategories.filter(x=>x.id!==b.dataset.delCat); renderWCategories();
  }));
  lucide.createIcons();
}

function renderFinish(){
  const name=document.getElementById('wName').value||'Bisnis';
  const type=document.getElementById('wType').value;
  document.getElementById('finishSummary').innerHTML=`
    <div class="flex items-center gap-3"><img src="${document.getElementById('wLogo').value}" class="w-10 h-10 rounded-xl border bg-white"/><div><div class="font-bold">${name}</div><div class="text-xs text-slate-500">${type} • ${wAccounts.length} akun • ${wCategories.length} kategori</div></div></div>
    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
      <div class="bg-white border rounded-xl p-2"><div class="font-bold">Accounts</div><div>${wAccounts.map(a=> a.name+' ('+fmtIDR(a.opening)+')').join(', ')}</div></div>
      <div class="bg-white border rounded-xl p-2"><div class="font-bold">Financial</div><div>COGS ${document.getElementById('wCOGS').checked?'ON':'OFF'} • Assets ${document.getElementById('wAssets').checked?'ON':'OFF'}</div></div>
    </div>
  `;
}

function updateThemeUISetup(isDark){
  document.querySelectorAll('.icon-moon').forEach(el=> el.classList.toggle('hidden', isDark));
  document.querySelectorAll('.icon-sun').forEach(el=> el.classList.toggle('hidden', !isDark));
  if(window.lucide) lucide.createIcons();
}
function applyThemeSetup(isDark){
  document.documentElement.classList.toggle('dark', isDark);
  try{ localStorage.setItem('keukita_theme', isDark ? 'dark' : 'light'); }catch(e){}
  updateThemeUISetup(isDark);
}
document.addEventListener('DOMContentLoaded', ()=>{
  updateThemeUISetup(document.documentElement.classList.contains('dark'));
  document.getElementById('btnThemeToggle')?.addEventListener('click', ()=>{
    const isDark = !document.documentElement.classList.contains('dark');
    applyThemeSetup(isDark);
  });
  try{
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e)=>{
      const saved = localStorage.getItem('keukita_theme');
      if(!saved) applyThemeSetup(e.matches);
    });
  }catch(e){}
  loadTemplate(document.getElementById('wType').value);
  renderStep(); renderTemplatePreview();
  document.getElementById('wType').addEventListener('change', ()=>{ loadTemplate(document.getElementById('wType').value); renderTemplatePreview(); renderStep(); });
  document.getElementById('wLogo').addEventListener('input', e=> document.getElementById('wLogoPreview').src=e.target.value);
  document.getElementById('btnPickLogo').addEventListener('click', ()=>{
    const seeds=['kopi','bakery','travel','retail','service','shop','laundry'];
    const s=seeds[Math.floor(Math.random()*seeds.length)]+Math.floor(Math.random()*999);
    const url=`https://api.dicebear.com/7.x/shapes/svg?seed=${s}`;
    document.getElementById('wLogo').value=url; document.getElementById('wLogoPreview').src=url;
  });
  document.getElementById('btnAddWAcc').addEventListener('click', ()=>{
    const name=document.getElementById('wAccName').value.trim();
    const type=document.getElementById('wAccType').value;
    const opening=parseAmount(document.getElementById('wAccOpening').value);
    if(!name) return alert('Nama akun wajib');
    wAccounts.push({id:'acc_'+uid(), name, type, opening});
    document.getElementById('wAccName').value=''; document.getElementById('wAccOpening').value=''; renderWAccounts();
  });
  document.getElementById('wAccOpening').addEventListener('input', e=>{ e.target.value = parseAmount(e.target.value)? fmtNum(parseAmount(e.target.value)):''; });
  document.querySelectorAll('.wcat-tab').forEach(b=> b.addEventListener('click', ()=>{
    document.querySelectorAll('.wcat-tab').forEach(x=>{ x.classList.remove('bg-slate-900','text-white'); x.classList.add('bg-slate-100'); });
    b.classList.add('bg-slate-900','text-white'); b.classList.remove('bg-slate-100'); renderWCategories();
  }));
  document.getElementById('btnAddWCat').addEventListener('click', ()=>{
    const name=document.getElementById('wCatName').value.trim();
    const type=document.getElementById('wCatType').value;
    const cls=document.getElementById('wCatClass').value;
    if(!name) return alert('Nama kategori wajib');
    wCategories.push({id:'cat_'+uid(), name, type, classification:cls, affects:true});
    document.getElementById('wCatName').value=''; renderWCategories();
  });
  document.getElementById('btnNext').addEventListener('click', ()=>{
    if(step===1){
      const name=document.getElementById('wName').value.trim();
      if(!name) return alert('Nama bisnis wajib');
    }
    if(step<5){ step++; renderStep(); window.scrollTo({top:0,behavior:'smooth'}); }
  });
  document.getElementById('btnPrev').addEventListener('click', ()=>{ if(step>1){ step--; renderStep(); }});
  document.getElementById('btnBackTo4').addEventListener('click', ()=>{ step=4; renderStep(); });
  document.getElementById('btnFinish').addEventListener('click', ()=>{
    // Build state to persist
    const business={id:'biz_'+uid(), name: document.getElementById('wName').value.trim(), type: document.getElementById('wType').value, logo: document.getElementById('wLogo').value, currency: document.getElementById('wCurrency').value, timezone: document.getElementById('wTz').value };
    const accounts=wAccounts.map(a=> ({id:a.id, name:a.name, type:a.type, opening:a.opening, archived:false}));
    const categories=wCategories.map(c=> ({id:c.id, name:c.name, type:c.type, classification:c.classification, affects_profit: c.type==='INCOME'? true : !!c.affects, archived:false}));
    // ensure non-profit defaults
    if(!categories.some(c=> c.name==='Asset Purchase')) categories.push({id:'cat_'+uid(), name:'Asset Purchase', type:'EXPENSE', classification:'Asset', affects_profit:false, archived:false});
    if(!categories.some(c=> c.name==='Owner Withdrawal')) categories.push({id:'cat_'+uid(), name:'Owner Withdrawal', type:'EXPENSE', classification:'Other', affects_profit:false, archived:false});
    const settings={cogs: document.getElementById('wCOGS').checked, assets: document.getElementById('wAssets').checked, tax: document.getElementById('wTax').checked, receivable: document.getElementById('wReceivable').checked, payable: document.getElementById('wPayable').checked};
    const state={
      business, accounts, categories,
      transactions: [], assets:[],
      audit:[{id:'au_'+uid(), business_id: business.id, user:'Owner', action:'BUSINESS_CREATED', entity:'business', entity_id: business.id, detail:`Business ${business.name} (${business.type}) dibuat via wizard`, created_at: new Date().toISOString()}],
      settings
    };
    // Seed demo transactions if coffee shop? keep empty to show empty states? But PRD wants quick start, so seed one income
    // Do not seed to showcase empty states? We'll seed minimal
    if(accounts.length){
      // add opening balances as not transactions - handled via accountBalance
    }
    localStorage.setItem(KEY, JSON.stringify(state));
    // Laravel Blade provides dashboardUrl, fallback to index.html for static
    location.href = window.dashboardUrl || 'index.html';
  });
  if(window.lucide) lucide.createIcons();
});
