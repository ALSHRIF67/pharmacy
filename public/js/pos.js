// ======================= IndexedDB (Dexie) =======================
const db = new Dexie('PharmaPos_v3');
db.version(1).stores({
  products:   '++lid, id, barcode, name',
  sales:      '++id, total_price, synched, created_at',
  sync_queue: '++id, type, timestamp'
});

// ======================= Global State =======================
let allProducts = [];
let cart = [];
let currentPage = 1;
let qrScanner = null;
let qrActive = false;

// Helper functions
function toast(msg, type = 'i') {
  const t = document.createElement('div');
  t.className = 'toast t' + type;
  t.textContent = msg;
  document.getElementById('toasts').appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

const fmt = n => parseFloat(n || 0).toFixed(2);

// ======================= Load Products =======================
async function loadProducts() {
  const grid = document.getElementById('prod-grid');
  if (grid) grid.innerHTML = '<div class="g-load"><div class="spin"></div><p>جاري التحميل...</p></div>';
  try {
    const res = await fetch('/api/products', { headers: { Accept: 'application/json' } });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    allProducts = await res.json();
    await db.products.clear();
    for (const p of allProducts) await db.products.put({ ...p, lid: undefined });
    const countSpan = document.getElementById('prod-count');
    if (countSpan) countSpan.textContent = allProducts.length + ' منتج';
  } catch (err) {
    console.warn('API offline, loading IndexedDB:', err);
    const local = await db.products.toArray();
    if (local.length) {
      allProducts = local.map(p => ({ ...p, price: p.price ?? 0 }));
      if (document.getElementById('prod-count')) document.getElementById('prod-count').textContent = allProducts.length + ' منتج (محلي)';
      toast('بيانات محلية — تحقق من الاتصال', 'w');
    } else {
      if (grid) grid.innerHTML = '<div class="g-empty">⚠️ لا توجد منتجات — تحقق من الاتصال أو أضف منتجاً</div>';
      if (document.getElementById('prod-count')) document.getElementById('prod-count').textContent = 'لا توجد منتجات';
      return;
    }
  }
  renderGrid();
  if (document.getElementById('tbl-body')) renderTable();
}

// ======================= Render Grid =======================
function renderGrid() {
  const srch = document.getElementById('srch');
  const q = srch ? srch.value.trim().toLowerCase() : '';
  const list = q ? allProducts.filter(p =>
    (p.name || '').toLowerCase().includes(q) ||
    (p.barcode || '').toLowerCase().includes(q) ||
    (p.category || '').toLowerCase().includes(q)
  ) : allProducts;

  const grid = document.getElementById('prod-grid');
  if (!grid) return;
  if (!list.length) {
    grid.innerHTML = '<div class="g-empty">🔍 لا توجد نتائج</div>';
    return;
  }
  grid.innerHTML = '';
  list.forEach(p => {
    const card = document.createElement('div');
    card.className = 'pcard';
    card.innerHTML = `
      <span class="pc-cat">${p.category || 'عام'}</span>
      <span class="pc-name">${escapeHtml(p.name)}</span>
      <span class="pc-bc">${p.barcode}</span>
      <span class="pc-price">${fmt(p.price)} ر.س</span>
      <button class="pc-edit" data-bc="${p.barcode}">✏️ تعديل</button>`;
    card.addEventListener('click', e => {
      if (e.target.classList.contains('pc-edit')) {
        e.stopPropagation();
        window.location.href = `/pos/${p.id}/edit`;
      } else {
        addToCart(p);
      }
    });
    grid.appendChild(card);
  });
}

function escapeHtml(str) { return String(str).replace(/[&<>]/g, function(m){ if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

// ======================= Render Table =======================
function renderTable() {
  const tsrch = document.getElementById('tsrch');
  const q = tsrch ? tsrch.value.trim().toLowerCase() : '';
  const list = q ? allProducts.filter(p =>
    (p.name || '').toLowerCase().includes(q) ||
    (p.barcode || '').toLowerCase().includes(q)
  ) : allProducts;

  const total = Math.max(1, Math.ceil(list.length / 10));
  if (currentPage > total) currentPage = total;
  const slice = list.slice((currentPage - 1) * 10, currentPage * 10);

  const tbody = document.getElementById('tbl-body');
  if (!tbody) return;
  if (!slice.length) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted)">لا توجد منتجات</td></tr>';
  } else {
    tbody.innerHTML = slice.map((p, i) => `
      <tr>
        <td style="color:var(--muted)">${(currentPage-1)*10+i+1}</td>
        <td class="tbl-nm">${escapeHtml(p.name)}</td>
        <td class="tbl-bc">${p.barcode}</td>
        <td><span class="tbl-cat">${p.category || '—'}</span></td>
        <td class="tbl-pr">${fmt(p.price)} ر.س</td>
        <td style="color:var(--muted)">${p.stock ?? '—'}</td>
        <td>
          <div class="tbl-acts">
            <a href="/pos/${p.id}" class="btn btn-g btn-sm">👁 عرض</a>
            <a href="/pos/${p.id}/edit" class="btn btn-w btn-sm">✏️ تعديل</a>
            <button class="btn btn-d btn-sm" onclick="confirmDelete(${p.id},'${escapeHtml(p.name)}','${p.barcode}')">🗑 حذف</button>
          </div>
        </td>
      </tr>`).join('');
  }
  const pgInfo = document.getElementById('pg-info');
  if (pgInfo) pgInfo.textContent = `${currentPage} / ${total}`;
  const prevBtn = document.getElementById('pg-prev');
  const nextBtn = document.getElementById('pg-next');
  if (prevBtn) prevBtn.disabled = currentPage <= 1;
  if (nextBtn) nextBtn.disabled = currentPage >= total;
}

function changePage(d) { currentPage += d; renderTable(); }

// ======================= Cart =======================
function addToCart(product) {
  const key = product.barcode || String(product.id);
  const existing = cart.find(i => (i.barcode || String(i.id)) === key);
  if (existing) existing.qty++;
  else cart.push({ ...product, qty: 1 });
  renderCart();
}

function renderCart() {
  const tbody = document.getElementById('cart-items');
  const body = document.getElementById('cart-body');
  const table = document.querySelector('#cart-body table');
  const emptyMsg = document.getElementById('cart-empty');
  const totEl = document.getElementById('cart-tot');
  const cntEl = document.getElementById('cart-cnt');
  const chkBtn = document.getElementById('chk-btn');
  
  if (!body && !tbody) return;

  // New Table Structure
  if (tbody) {
    if (!cart.length) {
      if (table) table.style.display = 'none';
      if (emptyMsg) emptyMsg.style.display = 'block';
      if (totEl) totEl.textContent = '0.00 ر.س';
      if (cntEl) cntEl.textContent = '0';
      if (chkBtn) chkBtn.disabled = true;
      return;
    }

    if (table) table.style.display = 'table';
    if (emptyMsg) emptyMsg.style.display = 'none';

    let total = 0;
    tbody.innerHTML = '';
    cart.forEach((item, idx) => {
      const price = parseFloat(item.price) || 0;
      const sub = price * item.qty;
      total += sub;
      const row = document.createElement('tr');
      row.className = 'border-b hover:bg-gray-50';
      row.innerHTML = `
        <td class="text-right py-2 px-2">${escapeHtml(item.name)}</td>
        <td class="text-center py-2 px-2">
          <div class="ci-ctrl" style="justify-content: center;">
            <button class="qbtn" data-i="${idx}" data-a="dec">−</button>
            <span class="qnum">${item.qty}</span>
            <button class="qbtn" data-i="${idx}" data-a="inc">+</button>
          </div>
        </td>
        <td class="text-center py-2 px-2 text-muted">${fmt(price)}</td>
        <td class="text-center py-2 px-2" style="font-weight:bold;">${fmt(sub)}</td>
        <td class="text-center py-2 px-2">
          <button class="delbtn" data-i="${idx}" style="color:var(--red); font-size:18px;">🗑</button>
        </td>`;
      tbody.appendChild(row);
    });

    tbody.querySelectorAll('.qbtn').forEach(btn => btn.addEventListener('click', () => {
      const i = +btn.dataset.i;
      if (btn.dataset.a === 'inc') cart[i].qty++;
      else if (cart[i].qty > 1) cart[i].qty--;
      else cart.splice(i, 1);
      renderCart();
    }));
    tbody.querySelectorAll('.delbtn').forEach(btn => btn.addEventListener('click', () => {
      cart.splice(+btn.dataset.i, 1);
      renderCart();
    }));

    if (totEl) totEl.textContent = fmt(total) + ' ر.س';
    if (cntEl) cntEl.textContent = cart.reduce((s, i) => s + i.qty, 0);
    if (chkBtn) chkBtn.disabled = false;
    return;
  }

  // Fallback for old div structure
  if (!cart.length) {
    body.innerHTML = '<div class="cart-e"><div class="eico">🛍️</div><p>السلة فارغة</p><small style="color:var(--muted)">انقر منتجاً لإضافته</small></div>';
    if (totEl) totEl.textContent = '0.00 ر.س';
    if (cntEl) cntEl.textContent = '0';
    if (chkBtn) chkBtn.disabled = true;
    return;
  }

  let total = 0;
  body.innerHTML = '';
  cart.forEach((item, idx) => {
    const price = parseFloat(item.price) || 0;
    const sub = price * item.qty;
    total += sub;
    const row = document.createElement('div');
    row.className = 'citem';
    row.innerHTML = `
      <div class="ci-inf">
        <div class="ci-nm">${escapeHtml(item.name)}</div>
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

  if (totEl) totEl.textContent = fmt(total) + ' ر.س';
  if (cntEl) cntEl.textContent = cart.reduce((s, i) => s + i.qty, 0);
  if (chkBtn) chkBtn.disabled = false;
}

// ======================= Checkout =======================
document.addEventListener('click', function(e) {
  if (e.target.id === 'chk-btn' || e.target.closest('#chk-btn')) {
    checkout();
  }
});

async function checkout() {
  if (!cart.length) return;
  const btn = document.getElementById('chk-btn');
  if (btn) { btn.disabled = true; btn.textContent = '⏳ جاري...'; }

  const total = cart.reduce((s, i) => s + parseFloat(i.price) * i.qty, 0);
  const invData = {
    id: 'INV-' + Date.now(),
    date: new Date().toLocaleString('ar-SA'),
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
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const res = await fetch('/api/sales', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
      body: JSON.stringify(payload)
    });
    if (res.ok) {
        toast('✅ تمت عملية البيع بنجاح', 's');
    } else {
        const errData = await res.json().catch(() => ({}));
        throw new Error(errData.message || 'Server ' + res.status);
    }
  } catch (err) {
    if (err.message && !err.message.startsWith('Server ') && err.message !== 'Failed to fetch') {
        toast('❌ خطأ: ' + err.message, 'e');
        if (btn) { btn.disabled = false; btn.textContent = 'إتمام البيع'; }
        return;
    } else {
        await db.sales.add({ ...payload, synched: 0 });
        await db.sync_queue.add({ type: 'SALE_CREATE', data: payload, timestamp: new Date() });
        toast('📦 تم الحفظ محلياً — مزامنة لاحقاً', 'i');
    }
  }

  cart = [];
  renderCart();
  if (btn) btn.textContent = 'إتمام البيع';
  showInvoice(invData);
}

function showInvoice(data) {
  const rows = data.items.map(i => `
    <tr>
      <td>${escapeHtml(i.name)}</td>
      <td style="text-align:center">${i.qty}</td>
      <td style="text-align:left">${fmt(i.price)} ر.س</td>
      <td style="text-align:left;font-weight:700">${fmt(i.price * i.qty)} ر.س</td>
    </tr>`).join('');
  const content = document.getElementById('inv-content');
  if (content) {
    content.innerHTML = `
      <div class="inv-hdr">
        <h3>💊 صيدلية</h3>
        <p>فاتورة مبيعات رسمية</p>
      </div>
      <div class="inv-meta">
        <span>رقم الفاتورة: <strong>${data.id}</strong></span>
        <span>${data.date}</span>
      </div>
      <table class="inv-tbl">
        <thead><tr><th>المنتج</th><th style="text-align:center">الكمية</th><th style="text-align:left">السعر</th><th style="text-align:left">الإجمالي</th></tr></thead>
        <tbody>${rows}</tbody>
      </table>
      <div class="inv-tot">
        <span class="inv-tot-lbl">المجموع الكلي</span>
        <span class="inv-tot-amt">${fmt(data.total)} ر.س</span>
      </div>`;
  }
  openModal('inv-modal');
}

// ======================= Barcode Input =======================
document.addEventListener('DOMContentLoaded', function() {
  const bcInput = document.getElementById('barcode-inp');
  if (bcInput) {
    bcInput.addEventListener('keydown', async e => {
      if (e.key !== 'Enter') return;
      const val = e.target.value.trim();
      e.target.value = '';
      if (val) await handleBarcode(val);
    });
  }
});

async function handleBarcode(barcode) {
  let p = allProducts.find(p => p.barcode === barcode);
  if (!p) p = await db.products.where('barcode').equals(barcode).first();
  if (p) {
    addToCart(p);
    toast('تمت الإضافة: ' + p.name, 's');
  } else {
    window.location.href = `/pos/create?barcode=${encodeURIComponent(barcode)}`;
    toast('منتج غير موجود — أضف المنتج أولاً', 'i');
  }
}

// ======================= QR Scanner =======================
async function toggleQr() { qrActive ? stopQr() : startQr(); }

async function startQr() {
  if (typeof Html5Qrcode === 'undefined') { toast('مكتبة QR غير محملة', 'e'); return; }
  const wrap = document.getElementById('qr-wrap');
  if (wrap) wrap.classList.add('open');
  qrScanner = new Html5Qrcode('reader');
  try {
    await qrScanner.start(
      { facingMode: 'environment' },
      { fps: 10, qrbox: { width: 250, height: 100 } },
      async decoded => { stopQr(); const bcInput = document.getElementById('barcode-inp'); if(bcInput) bcInput.value = decoded; await handleBarcode(decoded); },
      () => {}
    );
    qrActive = true;
    const qrBtn = document.getElementById('qr-btn');
    if (qrBtn) qrBtn.textContent = '⏹ إيقاف QR';
    toast('الكاميرا نشطة — صوّب على الباركود', 'i');
  } catch (err) {
    if (wrap) wrap.classList.remove('open');
    toast('تعذّر الوصول للكاميرا: ' + (err.message || err), 'e');
  }
}

async function stopQr() {
  if (qrScanner && qrActive) { try { await qrScanner.stop(); } catch {} qrScanner = null; }
  qrActive = false;
  const wrap = document.getElementById('qr-wrap');
  if (wrap) wrap.classList.remove('open');
  const qrBtn = document.getElementById('qr-btn');
  if (qrBtn) qrBtn.textContent = '📷 مسح QR';
}

// ======================= Delete Confirmation =======================
let pendingDelete = null;
function confirmDelete(id, name, barcode) {
  pendingDelete = { id, barcode };
  const msg = document.getElementById('del-msg');
  if (msg) msg.textContent = `هل أنت متأكد من حذف المنتج "${name}"؟ لا يمكن التراجع عن هذه العملية.`;
  const modal = document.getElementById('del-modal');
  if (modal) modal.classList.add('open');
}

async function doDelete() {
  if (!pendingDelete) return;
  const { id, barcode } = pendingDelete;
  allProducts = allProducts.filter(p => p.barcode !== barcode && p.id != id);
  await db.products.where('barcode').equals(barcode).delete();
  await db.sync_queue.add({ type: 'PRODUCT_DELETE', data: { id, barcode }, timestamp: new Date() });
  try {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    await fetch(`/products/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' } });
  } catch { /* queued */ }
  renderGrid();
  if (document.getElementById('tbl-body')) renderTable();
  const countSpan = document.getElementById('prod-count');
  if (countSpan) countSpan.textContent = allProducts.length + ' منتج';
  toast('✅ تم حذف المنتج', 's');
  pendingDelete = null;
  closeModal('del-modal');
}

// ======================= Add to Cart from Show Page =======================
async function addToCartFromShow(productId) {
  const product = allProducts.find(p => p.id == productId);
  if (product) {
    addToCart(product);
    toast('تمت الإضافة: ' + product.name, 's');
  } else {
    toast('خطأ: المنتج غير موجود', 'e');
  }
}

// ======================= Connection Status =======================
function updateConn() {
  const on = navigator.onLine;
  const badge = document.getElementById('conn-badge');
  if (badge) {
    badge.style.background = on ? 'rgba(16,185,129,.15)' : 'rgba(239,68,68,.15)';
    badge.style.color = on ? 'var(--green)' : 'var(--red)';
    badge.style.borderColor = on ? 'rgba(16,185,129,.3)' : 'rgba(239,68,68,.3)';
    const dot = document.getElementById('cdot');
    if (dot) dot.style.background = on ? 'var(--green)' : 'var(--red)';
    const ctxt = document.getElementById('ctxt');
    if (ctxt) ctxt.textContent = on ? 'متصل' : 'غير متصل';
  }
}
window.addEventListener('online', () => { updateConn(); loadProducts(); });
window.addEventListener('offline', updateConn);
updateConn();

// ======================= Initialization =======================
function initPOS() {
  loadProducts();
  const bcInput = document.getElementById('barcode-inp');
  if (bcInput) bcInput.focus();
}

// Expose globals
window.loadProducts = loadProducts;
window.renderGrid = renderGrid;
window.renderTable = renderTable;
window.changePage = changePage;
window.addToCart = addToCart;
window.toggleQr = toggleQr;
window.stopQr = stopQr;
window.confirmDelete = confirmDelete;
window.doDelete = doDelete;
window.addToCartFromShow = addToCartFromShow;
window.initPOS = initPOS;
window.currentPage = currentPage;

// Auto-init if on POS page
if (document.getElementById('pos-view')) {
  document.addEventListener('DOMContentLoaded', initPOS);
}
