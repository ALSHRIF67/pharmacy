<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>نقطة البيع — الصيدلية</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<script src="https://unpkg.com/dexie/dist/dexie.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<style>
/* ─── RESET ─────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

/* ─── TOKENS ─────────────────────────────────── */
:root{
  --bg:#0f1117;--surf:#1a1d27;--surf2:#22263a;
  --accent:#6c63ff;--accent2:#8b85ff;
  --green:#10b981;--red:#ef4444;--yellow:#f59e0b;
  --text:#f1f5f9;--muted:#94a3b8;--border:#2d3148;
  --r:12px;
}

/* ─── BASE ─────────────────────────────────── */
body{font-family:'Cairo',sans-serif;background:var(--bg);color:var(--text);
  height:100vh;display:flex;flex-direction:column;overflow:hidden}

/* ─── HEADER ─────────────────────────────────── */
header{
  height:60px;display:flex;align-items:center;padding:0 18px;gap:14px;
  background:var(--surf);border-bottom:1px solid var(--border);flex-shrink:0;z-index:10;
}
.brand{display:flex;align-items:center;gap:8px}
.brand h1{font-size:16px;font-weight:700;color:var(--accent2);white-space:nowrap}

.tab-bar{display:flex;gap:3px;background:var(--bg);border-radius:8px;padding:3px}
.tab-btn{
  padding:5px 14px;border-radius:6px;border:none;cursor:pointer;
  font-family:'Cairo',sans-serif;font-size:13px;font-weight:600;
  transition:all .2s;background:transparent;color:var(--muted);
}
.tab-btn.active{background:var(--accent);color:#fff}

#hdr-mid{flex:1;text-align:center}
#prod-count{font-size:12px;color:var(--muted)}

#conn-badge{
  display:flex;align-items:center;gap:5px;padding:4px 10px;
  border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap;
  background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.3);
}
.dot{width:6px;height:6px;border-radius:50%;background:var(--green);animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}

/* ─── LAYOUT ─────────────────────────────────── */
#pos-view,#table-view{display:flex;flex:1;overflow:hidden}
.hidden{display:none!important}

/* ─── TOOLBAR ─────────────────────────────────── */
.toolbar{
  display:flex;align-items:center;gap:8px;padding:10px 14px;
  background:var(--surf);border-bottom:1px solid var(--border);flex-shrink:0;flex-wrap:wrap;
}
.sbox{flex:1;min-width:140px;position:relative}
.sbox input{
  width:100%;padding:8px 12px 8px 32px;background:var(--bg);
  border:1px solid var(--border);border-radius:8px;color:var(--text);
  font-family:'Cairo',sans-serif;font-size:13px;outline:none;transition:border .2s;
}
.sbox input:focus{border-color:var(--accent)}
.sico{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;pointer-events:none}

.btn{
  padding:8px 13px;border-radius:8px;border:none;cursor:pointer;
  font-family:'Cairo',sans-serif;font-size:13px;font-weight:600;
  transition:all .2s;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;
}
.btn-p{background:var(--accent);color:#fff}.btn-p:hover{background:var(--accent2)}
.btn-g{background:var(--surf2);color:var(--text)}.btn-g:hover{background:var(--border)}
.btn-s{background:var(--green);color:#fff}.btn-s:hover{opacity:.85}
.btn-d{background:var(--red);color:#fff}.btn-d:hover{opacity:.85}
.btn-w{background:var(--yellow);color:#111}.btn-w:hover{opacity:.85}
.btn-sm{padding:5px 10px;font-size:12px}

/* ─── PRODUCTS PANEL ─────────────────────────── */
#products-panel{flex:1;display:flex;flex-direction:column;overflow:hidden;border-left:1px solid var(--border)}

#barcode-inp{
  padding:8px 12px;background:var(--bg);border:1px solid var(--yellow);
  border-radius:8px;color:var(--text);font-family:'Cairo',sans-serif;
  font-size:13px;outline:none;width:175px;
}
#barcode-inp:focus{box-shadow:0 0 0 2px rgba(245,158,11,.2)}
#barcode-inp::placeholder{color:var(--muted);font-size:12px}

/* QR */
#qr-wrap{display:none;background:#000;position:relative;flex-shrink:0;max-height:200px}
#qr-wrap.open{display:block}
#reader{width:100%}
#qr-x{
  position:absolute;top:8px;left:8px;background:rgba(0,0,0,.6);
  border:none;color:#fff;font-size:18px;cursor:pointer;border-radius:6px;padding:1px 8px;z-index:5;
}

/* ─── PRODUCT GRID ─────────────────────────── */
#prod-grid{
  flex:1;overflow-y:auto;display:grid;
  grid-template-columns:repeat(auto-fill,minmax(148px,1fr));
  gap:10px;padding:14px;align-content:start;
}
#prod-grid::-webkit-scrollbar{width:4px}
#prod-grid::-webkit-scrollbar-thumb{background:var(--border);border-radius:3px}

.pcard{
  background:var(--surf);border:1px solid var(--border);border-radius:var(--r);
  padding:13px 10px;cursor:pointer;transition:all .2s;
  display:flex;flex-direction:column;gap:4px;position:relative;overflow:hidden;
}
.pcard::after{
  content:'';position:absolute;top:0;right:0;width:3px;height:100%;
  background:var(--accent);transform:scaleY(0);transition:transform .2s;transform-origin:top;
}
.pcard:hover{background:var(--surf2);border-color:var(--accent);transform:translateY(-2px);box-shadow:0 6px 20px rgba(108,99,255,.15)}
.pcard:hover::after{transform:scaleY(1)}
.pc-cat{font-size:10px;color:var(--accent2);font-weight:600;text-transform:uppercase}
.pc-name{font-size:13px;font-weight:700;line-height:1.3}
.pc-bc{font-size:10px;color:var(--muted);font-family:monospace}
.pc-price{font-size:15px;font-weight:900;color:var(--green);margin-top:2px}
.pc-edit{
  position:absolute;top:8px;left:8px;background:rgba(108,99,255,.2);
  color:var(--accent2);border:none;border-radius:6px;cursor:pointer;
  font-size:11px;padding:2px 7px;opacity:0;transition:opacity .2s;font-family:'Cairo',sans-serif;
}
.pcard:hover .pc-edit{opacity:1}

.g-empty,.g-load{grid-column:1/-1;text-align:center;padding:60px 0;color:var(--muted)}
.spin{
  width:32px;height:32px;border:3px solid var(--border);border-top-color:var(--accent);
  border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 10px;
}
@keyframes spin{to{transform:rotate(360deg)}}

/* ─── CART PANEL ─────────────────────────────── */
#cart-panel{
  width:345px;flex-shrink:0;display:flex;flex-direction:column;background:var(--surf);
}
.cart-hdr{
  display:flex;align-items:center;justify-content:space-between;
  padding:12px 14px;border-bottom:1px solid var(--border);flex-shrink:0;
}
.cart-hdr h2{font-size:14px;font-weight:700}
#cart-cnt{background:var(--accent);color:#fff;border-radius:20px;padding:2px 10px;font-size:11px;font-weight:700}
#cart-body{flex:1;overflow-y:auto;padding:8px}
#cart-body::-webkit-scrollbar{width:3px}
#cart-body::-webkit-scrollbar-thumb{background:var(--border)}
.cart-e{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;gap:8px;color:var(--muted)}
.cart-e .eico{font-size:40px;opacity:.3}

.citem{
  display:flex;align-items:center;gap:7px;padding:8px;
  border-radius:8px;background:var(--bg);margin-bottom:5px;border:1px solid var(--border);
}
.ci-inf{flex:1;min-width:0}
.ci-nm{font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ci-pr{font-size:11px;color:var(--muted)}
.ci-ctrl{display:flex;align-items:center;gap:4px}
.qbtn{
  width:22px;height:22px;border-radius:5px;border:1px solid var(--border);
  background:var(--surf2);color:var(--text);cursor:pointer;font-size:13px;
  display:flex;align-items:center;justify-content:center;transition:all .15s;
}
.qbtn:hover{background:var(--accent);border-color:var(--accent)}
.qnum{font-size:13px;font-weight:700;min-width:20px;text-align:center}
.ci-sub{font-size:12px;font-weight:700;color:var(--green);min-width:46px;text-align:left}
.delbtn{background:none;border:none;cursor:pointer;color:var(--muted);font-size:15px;transition:color .15s}
.delbtn:hover{color:var(--red)}

.cart-foot{padding:12px 14px;border-top:1px solid var(--border);flex-shrink:0}
.tot-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.tot-lbl{font-size:13px;color:var(--muted)}
#cart-tot{font-size:22px;font-weight:900;color:var(--green)}
#chk-btn{
  width:100%;padding:12px;background:linear-gradient(135deg,var(--green),#059669);
  color:#fff;border:none;border-radius:var(--r);font-family:'Cairo',sans-serif;
  font-size:15px;font-weight:700;cursor:pointer;transition:all .2s;
}
#chk-btn:hover:not(:disabled){transform:translateY(-1px);box-shadow:0 5px 16px rgba(16,185,129,.35)}
#chk-btn:disabled{opacity:.4;cursor:not-allowed}

/* ─── TABLE VIEW ─────────────────────────────── */
#table-view{flex-direction:column}
.tbl-wrap{flex:1;overflow-y:auto;padding:16px}
.tbl-wrap::-webkit-scrollbar{width:5px}
.tbl-wrap::-webkit-scrollbar-thumb{background:var(--border);border-radius:3px}

table.ptbl{
  width:100%;border-collapse:separate;border-spacing:0;
  background:var(--surf);border-radius:var(--r);overflow:hidden;border:1px solid var(--border);
}
.ptbl th{
  background:var(--surf2);padding:11px 14px;font-size:11px;font-weight:700;
  color:var(--muted);text-transform:uppercase;letter-spacing:.5px;
  text-align:right;border-bottom:1px solid var(--border);white-space:nowrap;
}
.ptbl td{padding:11px 14px;font-size:13px;border-bottom:1px solid var(--border);vertical-align:middle}
.ptbl tr:last-child td{border-bottom:none}
.ptbl tr:hover td{background:var(--surf2)}
.tbl-nm{font-weight:600}
.tbl-bc{font-family:monospace;font-size:12px;color:var(--muted)}
.tbl-pr{font-weight:700;color:var(--green);white-space:nowrap}
.tbl-cat{font-size:11px;background:rgba(108,99,255,.15);color:var(--accent2);padding:2px 8px;border-radius:20px;white-space:nowrap}
.tbl-acts{display:flex;gap:5px;align-items:center;flex-wrap:wrap}

.pager{display:flex;align-items:center;justify-content:center;gap:6px;padding:14px;flex-shrink:0}
.pgbtn{
  width:32px;height:32px;border-radius:6px;border:1px solid var(--border);
  background:var(--surf);color:var(--text);cursor:pointer;font-family:'Cairo',sans-serif;
  font-size:13px;font-weight:600;transition:all .15s;
}
.pgbtn:hover{background:var(--accent);border-color:var(--accent);color:#fff}
.pgbtn.active{background:var(--accent);border-color:var(--accent);color:#fff}
.pgbtn:disabled{opacity:.4;cursor:not-allowed}
#pg-info{font-size:12px;color:var(--muted);padding:0 6px}

/* ─── MODALS ─────────────────────────────────── */
.mo{
  position:fixed;inset:0;background:rgba(0,0,0,.72);backdrop-filter:blur(4px);
  display:none;align-items:center;justify-content:center;z-index:500;
}
.mo.open{display:flex}
.mbox{
  background:var(--surf);border:1px solid var(--border);border-radius:16px;
  width:100%;margin:16px;overflow:hidden;animation:slideUp .25s ease;
}
@keyframes slideUp{from{transform:translateY(20px);opacity:0}to{transform:translateY(0);opacity:1}}
.mhd{
  display:flex;justify-content:space-between;align-items:center;
  padding:15px 18px;border-bottom:1px solid var(--border);
}
.mhd h2{font-size:15px;font-weight:700}
.mcls{background:none;border:none;cursor:pointer;color:var(--muted);font-size:20px;line-height:1;transition:color .15s}
.mcls:hover{color:var(--red)}
.mbdy{padding:18px;max-height:72vh;overflow-y:auto}
.mbdy::-webkit-scrollbar{width:4px}
.mbdy::-webkit-scrollbar-thumb{background:var(--border)}
.mftr{display:flex;gap:8px;justify-content:flex-end;padding:12px 18px;border-top:1px solid var(--border)}

/* form */
.fg{margin-bottom:13px}
.fg label{display:block;font-size:11px;font-weight:600;color:var(--muted);margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px}
.fg input,.fg select{
  width:100%;padding:9px 12px;background:var(--bg);border:1px solid var(--border);
  border-radius:8px;color:var(--text);font-family:'Cairo',sans-serif;font-size:13px;
  outline:none;transition:border .2s;
}
.fg input:focus,.fg select:focus{border-color:var(--accent)}
.fg input.err{border-color:var(--red)}
.ferr{font-size:11px;color:var(--red);margin-top:3px;display:none}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:10px}

/* show-modal info */
.irow{display:flex;align-items:baseline;gap:10px;padding:9px 0;border-bottom:1px solid var(--border)}
.irow:last-child{border-bottom:none}
.ilbl{font-size:12px;color:var(--muted);font-weight:600;min-width:100px}
.ival{font-size:14px;font-weight:600}
.ival.price{color:var(--green);font-size:18px}

/* ─── INVOICE ─────────────────────────────────── */
.inv-hdr{text-align:center;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border)}
.inv-hdr h3{font-size:20px;font-weight:900}
.inv-hdr p{color:var(--muted);font-size:12px;margin-top:2px}
.inv-meta{display:flex;justify-content:space-between;margin-bottom:14px;font-size:12px;color:var(--muted)}
.inv-tbl{width:100%;border-collapse:collapse;margin-bottom:14px}
.inv-tbl th{background:var(--surf2);padding:7px 10px;font-size:11px;color:var(--muted);text-align:right;border-bottom:1px solid var(--border)}
.inv-tbl td{padding:8px 10px;border-bottom:1px solid var(--border);font-size:13px}
.inv-tbl tr:last-child td{border-bottom:none}
.inv-tot{display:flex;justify-content:space-between;align-items:center;padding:14px 0 0;border-top:2px solid var(--accent)}
.inv-tot-lbl{font-size:14px;font-weight:700}
.inv-tot-amt{font-size:24px;font-weight:900;color:var(--green)}

/* ─── TOAST ─────────────────────────────────── */
#toasts{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);z-index:9999;display:flex;flex-direction:column;gap:6px;pointer-events:none}
.toast{padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;color:#fff;box-shadow:0 4px 16px rgba(0,0,0,.4);animation:toastIn .3s ease;white-space:nowrap}
.ts{background:var(--green)}.te{background:var(--red)}.ti{background:var(--accent)}.tw{background:var(--yellow);color:#111}
@keyframes toastIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}

/* ─── PRINT ─────────────────────────────────── */
@media print{
  body>*:not(#inv-modal){display:none!important}
  #inv-modal{position:static!important;display:block!important}
  #inv-modal .mo{position:static!important;background:none!important;backdrop-filter:none!important;display:block!important}
  #inv-modal .mbox{border:none!important;box-shadow:none!important;max-width:100%!important;margin:0!important}
  .mhd button,.mftr{display:none!important}
  .inv-tbl th,.inv-tbl td,.ival,.inv-tot-lbl{color:#000!important}
  .inv-tot-amt{color:#000!important}
  body{background:#fff!important;color:#000!important}
}
</style>
</head>
<body>

<!-- ══════════════════════════ HEADER ══════════════════════════ -->
<header>
  <div class="brand"><span>💊</span><h1>صيدلية — نقطة البيع</h1></div>
  <div class="tab-bar">
    <button class="tab-btn active" id="tab-pos"   onclick="switchTab('pos')">🛒 البيع</button>
    <button class="tab-btn"        id="tab-table" onclick="switchTab('table')">📋 المنتجات</button>
  </div>
  <div id="hdr-mid"><span id="prod-count">جاري التحميل...</span></div>
  <div id="conn-badge"><div class="dot" id="cdot"></div><span id="ctxt">متصل</span></div>
</header>

<!-- ══════════════════════════ POS VIEW ══════════════════════════ -->
<div id="pos-view">

  <!-- Products Panel -->
  <div id="products-panel">
    <div class="toolbar">
      <div class="sbox">
        <span class="sico">🔍</span>
        <input type="text" id="srch" placeholder="بحث بالاسم أو الباركود..." oninput="renderGrid()">
      </div>
      <input type="text" id="barcode-inp" placeholder="📷 باركود + Enter">
      <button class="btn btn-w" id="qr-btn" onclick="toggleQr()">📷 مسح QR</button>
      <button class="btn btn-g" onclick="loadProducts()" title="تحديث">🔄</button>
    </div>
    <!-- QR Reader -->
    <div id="qr-wrap"><button id="qr-x" onclick="stopQr()">✕</button><div id="reader"></div></div>
    <!-- Grid -->
    <div id="prod-grid"><div class="g-load"><div class="spin"></div><p>جاري التحميل...</p></div></div>
  </div>

  <!-- Cart Panel -->
  <div id="cart-panel">
    <div class="cart-hdr"><h2>🛒 السلة</h2><span id="cart-cnt">0</span></div>
    <div id="cart-body">
      <div class="cart-e"><div class="eico">🛍️</div><p>السلة فارغة</p><small style="color:var(--muted)">انقر منتجاً لإضافته</small></div>
    </div>
    <div class="cart-foot">
      <div class="tot-row"><span class="tot-lbl">الإجمالي</span><span id="cart-tot">0.00 ر.س</span></div>
      <button id="chk-btn" disabled>✅ إتمام البيع</button>
    </div>
  </div>

</div><!-- /pos-view -->

<!-- ══════════════════════════ TABLE VIEW ══════════════════════════ -->
<div id="table-view" class="hidden">
  <div class="toolbar">
    <div class="sbox" style="flex:1">
      <span class="sico">🔍</span>
      <input type="text" id="tsrch" placeholder="بحث في المنتجات..." oninput="currentPage=1;renderTable()">
    </div>
    <button class="btn btn-p" onclick="openEditModal(null)">＋ منتج جديد</button>
    <button class="btn btn-g" onclick="loadProducts()">🔄 تحديث</button>
  </div>
  <div class="tbl-wrap">
    <table class="ptbl">
      <thead>
        <tr>
          <th>#</th><th>الاسم</th><th>الباركود</th><th>التصنيف</th>
          <th>السعر</th><th>المخزون</th><th>الإجراءات</th>
        </tr>
      </thead>
      <tbody id="tbl-body">
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted)"><div class="spin" style="margin:auto"></div></td></tr>
      </tbody>
    </table>
  </div>
  <div class="pager">
    <button class="pgbtn" id="pg-prev" onclick="changePage(-1)">‹</button>
    <span id="pg-info">1 / 1</span>
    <button class="pgbtn" id="pg-next" onclick="changePage(1)">›</button>
  </div>
</div><!-- /table-view -->

<!-- ══════════════════════════ MODAL: EDIT / CREATE ══════════════════════════ -->
<div class="mo" id="edit-modal">
  <div class="mbox" style="max-width:490px">
    <div class="mhd"><h2 id="edit-title">منتج جديد</h2><button class="mcls" onclick="closeModal('edit-modal')">✕</button></div>
    <div class="mbdy">
      <form id="edit-form" onsubmit="submitEdit(event)">
        <input type="hidden" id="ef-id">
        <div class="fg">
          <label>الباركود *</label>
          <input type="text" id="ef-bc" placeholder="مثال: 1234567890" required>
          <span class="ferr" id="er-bc">الباركود مطلوب</span>
        </div>
        <div class="fg">
          <label>اسم المنتج *</label>
          <input type="text" id="ef-nm" placeholder="أدخل اسم المنتج" required>
          <span class="ferr" id="er-nm">الاسم مطلوب</span>
        </div>
        <div class="g2">
          <div class="fg">
            <label>السعر (ر.س) *</label>
            <input type="number" id="ef-pr" step="0.01" min="0.01" placeholder="0.00" required>
            <span class="ferr" id="er-pr">السعر يجب أن يكون أكبر من 0</span>
          </div>
          <div class="fg">
            <label>إضافة مخزون</label>
            <input type="number" id="ef-stk" value="0" min="0">
          </div>
        </div>
        <div class="g2">
          <div class="fg">
            <label>رقم الدفعة *</label>
            <input type="text" id="ef-bat" placeholder="BATCH-001" required>
            <span class="ferr" id="er-bat">رقم الدفعة مطلوب</span>
          </div>
          <div class="fg">
            <label>تاريخ الانتهاء *</label>
            <input type="date" id="ef-exp" required>
            <span class="ferr" id="er-exp">تاريخ الانتهاء مطلوب</span>
          </div>
        </div>
        <div class="fg">
          <label>ملاحظات</label>
          <input type="text" id="ef-note" placeholder="اختياري...">
        </div>
        <button type="submit" class="btn btn-p" id="ef-save" style="width:100%;padding:11px;font-size:14px;margin-top:4px">
          💾 حفظ المنتج
        </button>
      </form>
    </div>
  </div>
</div>

<!-- ══════════════════════════ MODAL: SHOW ══════════════════════════ -->
<div class="mo" id="show-modal">
  <div class="mbox" style="max-width:420px">
    <div class="mhd"><h2>تفاصيل المنتج</h2><button class="mcls" onclick="closeModal('show-modal')">✕</button></div>
    <div class="mbdy" id="show-body"></div>
    <div class="mftr">
      <button class="btn btn-g" onclick="closeModal('show-modal')">إغلاق</button>
      <button class="btn btn-w" id="show-edit-btn">✏️ تعديل</button>
      <button class="btn btn-s" id="show-cart-btn">🛒 إضافة للسلة</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════ MODAL: INVOICE ══════════════════════════ -->
<div class="mo" id="inv-modal">
  <div class="mbox" style="max-width:500px">
    <div class="mhd"><h2>🧾 الفاتورة</h2><button class="mcls" onclick="closeModal('inv-modal')">✕</button></div>
    <div class="mbdy"><div id="inv-content"></div></div>
    <div class="mftr">
      <button class="btn btn-g" onclick="closeModal('inv-modal')">إغلاق</button>
      <button class="btn btn-p" onclick="window.print()">🖨 طباعة</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════ MODAL: CONFIRM DELETE ══════════════════════════ -->
<div class="mo" id="del-modal">
  <div class="mbox" style="max-width:360px">
    <div class="mhd"><h2>⚠️ تأكيد الحذف</h2><button class="mcls" onclick="closeModal('del-modal')">✕</button></div>
    <div class="mbdy"><p id="del-msg" style="font-size:14px;line-height:1.7"></p></div>
    <div class="mftr">
      <button class="btn btn-g" onclick="closeModal('del-modal')">إلغاء</button>
      <button class="btn btn-d" id="del-ok">🗑 نعم، احذف</button>
    </div>
  </div>
</div>

<div id="toasts"></div>

<!-- ══════════════════════════ JAVASCRIPT ══════════════════════════ -->
<script>
// ════════════════════════════════════════════
// IndexedDB (Dexie)
// ════════════════════════════════════════════
const db = new Dexie('PharmaPos_v3');
db.version(1).stores({
  products:   '++lid, id, barcode, name',
  sales:      '++id, total_price, synched, created_at',
  sync_queue: '++id, type, timestamp'
});

// ════════════════════════════════════════════
// Constants & State
// ════════════════════════════════════════════
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
const PG_SIZE = 10;

let allProducts = [];
let cart        = [];
let currentPage = 1;
let currentView = 'pos';
let editTarget  = null;   // null = new product
let qrScanner   = null;
let qrActive    = false;

// ════════════════════════════════════════════
// Helpers
// ════════════════════════════════════════════
const $  = id => document.getElementById(id);
const fmt = n  => parseFloat(n || 0).toFixed(2);

function toast(msg, type = 'i') {
  const t = document.createElement('div');
  t.className = 'toast t' + type;
  t.textContent = msg;
  $('toasts').appendChild(t);
  setTimeout(() => t.remove(), 3500);
}
function openModal(id)  { $(id).classList.add('open');    }
function closeModal(id) { $(id).classList.remove('open'); }

// Close modal on backdrop click
document.querySelectorAll('.mo').forEach(m =>
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); })
);

// ════════════════════════════════════════════
// Tab Switching
// ════════════════════════════════════════════
function switchTab(v) {
  currentView = v;
  $('pos-view').classList.toggle('hidden', v !== 'pos');
  $('table-view').classList.toggle('hidden', v !== 'table');
  $('tab-pos').classList.toggle('active', v === 'pos');
  $('tab-table').classList.toggle('active', v === 'table');
  if (v === 'table') { currentPage = 1; renderTable(); }
}

// ════════════════════════════════════════════
// Load Products from API → IndexedDB
// ════════════════════════════════════════════
async function loadProducts() {
  $('prod-grid').innerHTML = '<div class="g-load"><div class="spin"></div><p>جاري التحميل...</p></div>';
  try {
    const res = await fetch('/api/products', { headers: { Accept: 'application/json' } });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    allProducts = await res.json();

    // Cache locally
    await db.products.clear();
    for (const p of allProducts) await db.products.put({ ...p, lid: undefined });

    $('prod-count').textContent = allProducts.length + ' منتج';
  } catch (err) {
    console.warn('API unavailable, loading IndexedDB:', err);
    const local = await db.products.toArray();
    if (local.length) {
      allProducts = local.map(p => ({ ...p, price: p.price ?? p.base_price ?? 0 }));
      $('prod-count').textContent = allProducts.length + ' منتج (محلي)';
      toast('بيانات محلية — تحقق من الاتصال', 'w');
    } else {
      $('prod-grid').innerHTML = '<div class="g-empty">⚠️ لا توجد منتجات — تحقق من الاتصال أو أضف منتجاً</div>';
      $('prod-count').textContent = 'لا توجد منتجات';
      return;
    }
  }
  renderGrid();
  if (currentView === 'table') renderTable();
}

// ════════════════════════════════════════════
// Render: Product Card Grid (POS view)
// ════════════════════════════════════════════
function renderGrid() {
  const q = ($('srch').value || '').trim().toLowerCase();
  const list = q
    ? allProducts.filter(p =>
        (p.name     || '').toLowerCase().includes(q) ||
        (p.barcode  || '').toLowerCase().includes(q) ||
        (p.category || '').toLowerCase().includes(q))
    : allProducts;

  const grid = $('prod-grid');
  if (!list.length) {
    grid.innerHTML = '<div class="g-empty">🔍 لا توجد نتائج</div>';
    return;
  }
  grid.innerHTML = '';
  list.forEach(p => {
    const c = document.createElement('div');
    c.className = 'pcard';
    c.innerHTML = `
      <span class="pc-cat">${p.category || 'عام'}</span>
      <span class="pc-name">${p.name}</span>
      <span class="pc-bc">${p.barcode}</span>
      <span class="pc-price">${fmt(p.price)} ر.س</span>
      <button class="pc-edit" data-bc="${p.barcode}">✏️ تعديل</button>`;
    c.addEventListener('click', e => {
      if (e.target.classList.contains('pc-edit')) {
        e.stopPropagation();
        openEditModal(findByBc(e.target.dataset.bc));
      } else {
        addToCart(p);
      }
    });
    grid.appendChild(c);
  });
}

// ════════════════════════════════════════════
// Render: Products Table (Table view)
// ════════════════════════════════════════════
function renderTable() {
  const q = ($('tsrch').value || '').trim().toLowerCase();
  const list = q
    ? allProducts.filter(p =>
        (p.name     || '').toLowerCase().includes(q) ||
        (p.barcode  || '').toLowerCase().includes(q) ||
        (p.category || '').toLowerCase().includes(q))
    : allProducts;

  const total = Math.max(1, Math.ceil(list.length / PG_SIZE));
  if (currentPage > total) currentPage = total;
  const slice = list.slice((currentPage - 1) * PG_SIZE, currentPage * PG_SIZE);

  const tbody = $('tbl-body');
  if (!slice.length) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted)">لا توجد منتجات</td></tr>';
  } else {
    tbody.innerHTML = slice.map((p, i) => `
      <tr>
        <td style="color:var(--muted)">${(currentPage-1)*PG_SIZE+i+1}</td>
        <td class="tbl-nm">${p.name}</td>
        <td class="tbl-bc">${p.barcode}</td>
        <td><span class="tbl-cat">${p.category || '—'}</span></td>
        <td class="tbl-pr">${fmt(p.price)} ر.س</td>
        <td style="color:var(--muted)">${p.stock ?? '—'}</td>
        <td>
          <div class="tbl-acts">
            <button class="btn btn-g btn-sm" onclick="openShowModal('${p.barcode}')">👁 عرض</button>
            <button class="btn btn-w btn-sm" onclick="openEditModal(findByBc('${p.barcode}'))">✏️ تعديل</button>
            <button class="btn btn-d btn-sm" onclick="confirmDelete(${p.id || 0},'${esc(p.name)}','${p.barcode}')">🗑 حذف</button>
          </div>
        </td>
      </tr>`).join('');
  }
  $('pg-info').textContent  = `${currentPage} / ${total}`;
  $('pg-prev').disabled = currentPage <= 1;
  $('pg-next').disabled = currentPage >= total;
}

function changePage(d) { currentPage += d; renderTable(); }
function esc(s) { return (s || '').replace(/'/g, "\\'"); }
function findByBc(bc) { return allProducts.find(p => p.barcode === bc); }
function findById(id) { return allProducts.find(p => p.id == id); }

// ════════════════════════════════════════════
// Barcode Input
// ════════════════════════════════════════════
$('barcode-inp').addEventListener('keydown', async e => {
  if (e.key !== 'Enter') return;
  const val = e.target.value.trim();
  e.target.value = '';
  if (val) await handleBarcode(val);
});

async function handleBarcode(barcode) {
  let p = findByBc(barcode);

  if (!p) {
    p = await db.products.where('barcode').equals(barcode).first();
  }
  if (!p) {
    try {
      const res = await fetch(`/api/products/barcode/${encodeURIComponent(barcode)}`, {
        headers: { Accept: 'application/json' }
      });
      if (res.ok) {
        p = await res.json();
        await db.products.put(p);
        allProducts.push(p);
        renderGrid();
      }
    } catch { /* offline */ }
  }

  if (p) {
    addToCart(p);
    toast('تمت الإضافة: ' + p.name, 's');
  } else {
    openEditModal({ barcode, name: '', price: 0 });
    toast('منتج غير موجود — أدخل البيانات', 'i');
  }
}

// ════════════════════════════════════════════
// QR Scanner
// ════════════════════════════════════════════
function toggleQr() { qrActive ? stopQr() : startQr(); }

async function startQr() {
  if (typeof Html5Qrcode === 'undefined') { toast('مكتبة QR غير محملة', 'e'); return; }
  $('qr-wrap').classList.add('open');
  qrScanner = new Html5Qrcode('reader');
  try {
    await qrScanner.start(
      { facingMode: 'environment' },
      { fps: 10, qrbox: { width: 250, height: 100 } },
      async decoded => { stopQr(); $('barcode-inp').value = decoded; await handleBarcode(decoded); },
      () => {}
    );
    qrActive = true;
    $('qr-btn').textContent = '⏹ إيقاف QR';
    toast('الكاميرا نشطة — صوّب على الباركود', 'i');
  } catch (err) {
    $('qr-wrap').classList.remove('open');
    toast('تعذّر الوصول للكاميرا: ' + (err.message || err), 'e');
  }
}

async function stopQr() {
  if (qrScanner && qrActive) { try { await qrScanner.stop(); } catch {} qrScanner = null; }
  qrActive = false;
  $('qr-wrap').classList.remove('open');
  $('qr-btn').textContent = '📷 مسح QR';
}

// ════════════════════════════════════════════
// Cart
// ════════════════════════════════════════════
function cartKey(p) { return p.barcode || String(p.id); }

function addToCart(product) {
  const ex = cart.find(i => cartKey(i) === cartKey(product));
  if (ex) ex.qty++;
  else cart.push({ ...product, qty: 1 });
  renderCart();
}

function renderCart() {
  const body   = $('cart-body');
  const totEl  = $('cart-tot');
  const cntEl  = $('cart-cnt');
  const chkBtn = $('chk-btn');

  if (!cart.length) {
    body.innerHTML = '<div class="cart-e"><div class="eico">🛍️</div><p>السلة فارغة</p><small style="color:var(--muted)">انقر منتجاً لإضافته</small></div>';
    totEl.textContent = '0.00 ر.س';
    cntEl.textContent = '0';
    chkBtn.disabled = true;
    return;
  }

  let total = 0;
  body.innerHTML = '';
  cart.forEach((item, idx) => {
    const price = parseFloat(item.price) || 0;
    const sub   = price * item.qty;
    total += sub;
    const row = document.createElement('div');
    row.className = 'citem';
    row.innerHTML = `
      <div class="ci-inf">
        <div class="ci-nm">${item.name}</div>
        <div class="ci-pr">${fmt(price)} ر.س / وحدة</div>
      </div>
      <div class="ci-ctrl">
        <button class="qbtn" data-i="${idx}" data-a="dec">−</button>
        <span class="qnum">${item.qty}</span>
        <button class="qbtn" data-i="${idx}" data-a="inc">+</button>
      </div>
      <span class="ci-sub">${fmt(sub)}</span>
      <button class="delbtn" data-i="${idx}">🗑</button>`;
    body.appendChild(row);
  });

  body.querySelectorAll('.qbtn').forEach(btn => btn.addEventListener('click', () => {
    const i = +btn.dataset.i;
    if (btn.dataset.a === 'inc') cart[i].qty++;
    else if (cart[i].qty > 1) cart[i].qty--;
    else cart.splice(i, 1);
    renderCart();
  }));
  body.querySelectorAll('.delbtn').forEach(btn => btn.addEventListener('click', () => {
    cart.splice(+btn.dataset.i, 1);
    renderCart();
  }));

  totEl.textContent = fmt(total) + ' ر.س';
  cntEl.textContent = cart.reduce((s, i) => s + i.qty, 0);
  chkBtn.disabled = false;
}

// ════════════════════════════════════════════
// Checkout
// ════════════════════════════════════════════
$('chk-btn').addEventListener('click', async () => {
  if (!cart.length) return;
  const btn = $('chk-btn');
  btn.disabled = true; btn.textContent = '⏳ جاري...';

  const total = cart.reduce((s, i) => s + parseFloat(i.price) * i.qty, 0);
  const invData = {
    id:    'INV-' + Date.now(),
    date:  new Date().toLocaleString('ar-SA'),
    items: cart.map(i => ({ name: i.name, qty: i.qty, price: parseFloat(i.price) })),
    total
  };

  const payload = {
    items: cart.map(i => ({ product_id: i.id, quantity: i.qty, price: parseFloat(i.price) })),
    total_price: total,
    created_at: new Date().toISOString(),
    synched: 0
  };

  try {
    const res = await fetch('/api/sales', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, Accept: 'application/json' },
      body: JSON.stringify(payload)
    });
    if (res.ok) toast('✅ تمت عملية البيع بنجاح', 's');
    else throw new Error('Server ' + res.status);
  } catch {
    await db.sales.add({ ...payload, synched: 0 });
    await db.sync_queue.add({ type: 'SALE_CREATE', data: payload, timestamp: new Date() });
    toast('📦 تم الحفظ محلياً — مزامنة لاحقاً', 'i');
  }

  cart = [];
  renderCart();
  btn.textContent = '✅ إتمام البيع';
  showInvoice(invData);
});

// ════════════════════════════════════════════
// Invoice
// ════════════════════════════════════════════
function showInvoice(data) {
  const rows = data.items.map(i => `
    <tr>
      <td>${i.name}</td>
      <td style="text-align:center">${i.qty}</td>
      <td style="text-align:left">${fmt(i.price)} ر.س</td>
      <td style="text-align:left;font-weight:700">${fmt(i.price * i.qty)} ر.س</td>
    </tr>`).join('');

  $('inv-content').innerHTML = `
    <div class="inv-hdr">
      <h3>💊 صيدلية</h3>
      <p>فاتورة مبيعات رسمية</p>
    </div>
    <div class="inv-meta">
      <span>رقم الفاتورة: <strong>${data.id}</strong></span>
      <span>${data.date}</span>
    </div>
    <table class="inv-tbl">
      <thead>
        <tr>
          <th>المنتج</th>
          <th style="text-align:center">الكمية</th>
          <th style="text-align:left">السعر</th>
          <th style="text-align:left">الإجمالي</th>
        </tr>
      </thead>
      <tbody>${rows}</tbody>
    </table>
    <div class="inv-tot">
      <span class="inv-tot-lbl">المجموع الكلي</span>
      <span class="inv-tot-amt">${fmt(data.total)} ر.س</span>
    </div>`;
  openModal('inv-modal');
}

// ════════════════════════════════════════════
// Edit / Create Modal
// ════════════════════════════════════════════
function openEditModal(product) {
  editTarget = product;
  const isNew = !product || !product.id;
  $('edit-title').textContent = isNew ? 'إضافة منتج جديد' : 'تعديل المنتج: ' + (product.name || '');
  $('ef-id').value   = product?.id      || '';
  $('ef-bc').value   = product?.barcode || '';
  $('ef-nm').value   = product?.name    || '';
  $('ef-pr').value   = product?.price   || '';
  $('ef-stk').value  = 0;
  $('ef-bat').value  = '';
  $('ef-exp').value  = '';
  $('ef-note').value = '';
  // Clear validation
  document.querySelectorAll('#edit-form .ferr').forEach(e => e.style.display = 'none');
  document.querySelectorAll('#edit-form input').forEach(i => i.classList.remove('err'));
  openModal('edit-modal');
  setTimeout(() => $('ef-nm').focus(), 150);
}

async function submitEdit(e) {
  e.preventDefault();

  // Validate
  const checks = [
    { inp: 'ef-bc',  err: 'er-bc',  ok: v => v.length > 0 },
    { inp: 'ef-nm',  err: 'er-nm',  ok: v => v.length > 0 },
    { inp: 'ef-pr',  err: 'er-pr',  ok: v => parseFloat(v) > 0 },
    { inp: 'ef-bat', err: 'er-bat', ok: v => v.length > 0 },
    { inp: 'ef-exp', err: 'er-exp', ok: v => v.length > 0 },
  ];
  let valid = true;
  checks.forEach(c => {
    const inp = $(c.inp);
    const ok  = c.ok(inp.value.trim());
    inp.classList.toggle('err', !ok);
    $(c.err).style.display = ok ? 'none' : 'block';
    if (!ok) valid = false;
  });
  if (!valid) return;

  // Check barcode uniqueness (if new product)
  const bc = $('ef-bc').value.trim();
  if (!$('ef-id').value) {
    const dup = findByBc(bc);
    if (dup) { $('ef-bc').classList.add('err'); $('er-bc').textContent = 'هذا الباركود مستخدم'; $('er-bc').style.display = 'block'; return; }
  }

  const data = {
    id:     $('ef-id').value || null,
    barcode: bc,
    name:   $('ef-nm').value.trim(),
    price:  parseFloat($('ef-pr').value),
    batch:  $('ef-bat').value.trim(),
    expiry: $('ef-exp').value,
    stock:  parseFloat($('ef-stk').value) || 0,
  };

  const saveBtn = $('ef-save');
  saveBtn.disabled = true; saveBtn.textContent = '⏳ جاري الحفظ...';

  try {
    const res = await fetch('/api/products/update', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, Accept: 'application/json' },
      body: JSON.stringify(data)
    });
    if (res.ok) {
      const result = await res.json();
      if (result.product?.id) data.id = result.product.id;
      toast('✅ تم الحفظ على الخادم', 's');
    } else {
      const err = await res.json().catch(() => ({}));
      throw new Error(err.message || 'فشل الحفظ على الخادم');
    }
  } catch (err) {
    await db.sync_queue.add({ type: 'PRODUCT_UPDATE', data, timestamp: new Date() });
    toast('📦 تم الحفظ محلياً — مزامنة لاحقاً', 'i');
  }

  // Update local state & cache
  await db.products.put({ ...data, lid: undefined });
  const idx = allProducts.findIndex(p => p.barcode === data.barcode || (data.id && p.id == data.id));
  if (idx >= 0) Object.assign(allProducts[idx], data);
  else { allProducts.unshift(data); }

  $('prod-count').textContent = allProducts.length + ' منتج';
  renderGrid();
  if (currentView === 'table') renderTable();
  closeModal('edit-modal');

  // If called from barcode scan (new product), add to cart
  if (!editTarget?.id) addToCart(data);

  saveBtn.disabled = false; saveBtn.textContent = '💾 حفظ المنتج';
}

// ════════════════════════════════════════════
// Show Modal
// ════════════════════════════════════════════
function openShowModal(barcode) {
  const p = findByBc(barcode);
  if (!p) return toast('المنتج غير موجود', 'e');
  $('show-body').innerHTML = `
    <div class="irow"><span class="ilbl">الاسم</span><span class="ival">${p.name}</span></div>
    <div class="irow"><span class="ilbl">الباركود</span><span class="ival" style="font-family:monospace">${p.barcode}</span></div>
    <div class="irow"><span class="ilbl">التصنيف</span><span class="ival">${p.category || '—'}</span></div>
    <div class="irow"><span class="ilbl">السعر</span><span class="ival price">${fmt(p.price)} ر.س</span></div>
    <div class="irow"><span class="ilbl">المخزون</span><span class="ival">${p.stock ?? '—'} وحدة</span></div>`;
  $('show-edit-btn').onclick = () => { closeModal('show-modal'); openEditModal(p); };
  $('show-cart-btn').onclick = () => { addToCart(p); closeModal('show-modal'); toast('تمت الإضافة: ' + p.name, 's'); };
  openModal('show-modal');
}

// ════════════════════════════════════════════
// Delete
// ════════════════════════════════════════════
function confirmDelete(id, name, barcode) {
  $('del-msg').textContent = `هل أنت متأكد من حذف المنتج "${name}"؟ لا يمكن التراجع عن هذه العملية.`;
  $('del-ok').onclick = () => doDelete(id, barcode);
  openModal('del-modal');
}

async function doDelete(id, barcode) {
  closeModal('del-modal');
  allProducts = allProducts.filter(p => p.barcode !== barcode && p.id != id);
  await db.products.where('barcode').equals(barcode).delete();
  await db.sync_queue.add({ type: 'PRODUCT_DELETE', data: { id, barcode }, timestamp: new Date() });

  // Attempt server delete
  try {
    await fetch(`/products/${id}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': CSRF, Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    });
  } catch { /* queued for sync */ }

  renderGrid();
  if (currentView === 'table') renderTable();
  $('prod-count').textContent = allProducts.length + ' منتج';
  toast('✅ تم حذف المنتج', 's');
}

// ════════════════════════════════════════════
// Connection Status
// ════════════════════════════════════════════
function updateConn() {
  const on = navigator.onLine;
  const badge = $('conn-badge');
  badge.style.background  = on ? 'rgba(16,185,129,.15)' : 'rgba(239,68,68,.15)';
  badge.style.color       = on ? 'var(--green)' : 'var(--red)';
  badge.style.borderColor = on ? 'rgba(16,185,129,.3)' : 'rgba(239,68,68,.3)';
  $('cdot').style.background = on ? 'var(--green)' : 'var(--red)';
  $('ctxt').textContent      = on ? 'متصل' : 'غير متصل';
}
window.addEventListener('online',  () => { updateConn(); loadProducts(); });
window.addEventListener('offline', updateConn);
updateConn();

// ════════════════════════════════════════════
// Boot
// ════════════════════════════════════════════
loadProducts();
$('barcode-inp').focus();
</script>
</body>
</html>
