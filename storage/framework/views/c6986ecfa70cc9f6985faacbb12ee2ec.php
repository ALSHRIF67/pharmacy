<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'نقطة البيع — الصيدلية'); ?></title>
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
          text-decoration:none;
        }
        .tab-btn.active{background:var(--accent);color:#fff}
        .tab-btn:hover{color:var(--text)}

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
        main{flex:1;overflow:auto;} /* Adjusted to auto so content pages can scroll if needed */

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
          text-decoration:none;
        }
        .btn-p{background:var(--accent);color:#fff}.btn-p:hover{background:var(--accent2)}
        .btn-g{background:var(--surf2);color:var(--text)}.btn-g:hover{background:var(--border)}
        .btn-s{background:var(--green);color:#fff}.btn-s:hover{opacity:.85}
        .btn-d{background:var(--red);color:#fff}.btn-d:hover{opacity:.85}
        .btn-w{background:var(--yellow);color:#111}.btn-w:hover{opacity:.85}
        .btn-sm{padding:5px 10px;font-size:12px}

        /* ─── PRODUCTS PANEL (POS) ─────────────────────────── */
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

        /* form styling for separate pages */
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

        .irow{display:flex;align-items:baseline;gap:10px;padding:9px 0;border-bottom:1px solid var(--border)}
        .irow:last-child{border-bottom:none}
        .ilbl{font-size:12px;color:var(--muted);font-weight:600;min-width:100px}
        .ival{font-size:14px;font-weight:600}
        .ival.price{color:var(--green);font-size:18px}

        /* invoice styles */
        .inv-hdr{text-align:center;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border)}
        .inv-hdr h3{font-size:20px;font-weight:900}
        .inv-hdr p{color:var(--muted);font-size:12px;margin-top:2px}
        .inv-meta{display:flex;justify-content:space-between;margin-bottom:14px;font-size:12px;color:var(--muted)}
        .inv-tbl{width:100%;border-collapse:collapse;margin-bottom:14px}
        .inv-tbl th{background:var(--surf2);padding:7px 10px;font-size:11px;color:var(--muted);text-align:right;border-bottom:1px solid var(--border)}
        .inv-tbl td{padding:8px 10px;border-bottom:1px solid var(--border);font-size:13px}
        .inv-tot{display:flex;justify-content:space-between;align-items:center;padding:14px 0 0;border-top:2px solid var(--accent)}
        .inv-tot-lbl{font-size:14px;font-weight:700}
        .inv-tot-amt{font-size:24px;font-weight:900;color:var(--green)}

        /* toast */
        #toasts{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);z-index:9999;display:flex;flex-direction:column;gap:6px;pointer-events:none}
        .toast{padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;color:#fff;box-shadow:0 4px 16px rgba(0,0,0,.4);animation:toastIn .3s ease;white-space:nowrap}
        .ts{background:var(--green)}.te{background:var(--red)}.ti{background:var(--accent)}.tw{background:var(--yellow);color:#111}
        @keyframes toastIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}

        /* print */
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

<header>
    <div class="brand"><span>💊</span><h1>صيدلية — نقطة البيع</h1></div>
    <div class="tab-bar">
        <a href="<?php echo e(route('pos.index')); ?>" class="tab-btn <?php if(Route::currentRouteName() === 'pos.index'): ?> active <?php endif; ?>">🛒 البيع</a>
        <a href="<?php echo e(route('pos.table')); ?>" class="tab-btn <?php if(Route::currentRouteName() === 'pos.table'): ?> active <?php endif; ?>">📋 المنتجات</a>
        <a href="<?php echo e(route('pos.create')); ?>" class="tab-btn <?php if(Route::currentRouteName() === 'pos.create'): ?> active <?php endif; ?>">＋ إضافة</a>
    </div>
    <div id="hdr-mid"><span id="prod-count">جاري التحميل...</span></div>
    <div id="conn-badge"><div class="dot" id="cdot"></div><span id="ctxt">متصل</span></div>
</header>

<main>
    <?php echo $__env->yieldContent('content'); ?>
</main>

<!-- Invoice Modal -->
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

<!-- Delete Confirmation Modal -->
<div class="mo" id="del-modal">
    <div class="mbox" style="max-width:360px">
        <div class="mhd"><h2>⚠️ تأكيد الحذف</h2><button class="mcls" onclick="closeModal('del-modal')">✕</button></div>
        <div class="mbdy"><p id="del-msg" style="font-size:14px;line-height:1.7"></p></div>
        <div class="mftr">
            <button class="btn btn-g" onclick="closeModal('del-modal')">إلغاء</button>
            <button class="btn btn-d" onclick="doDelete()">🗑 نعم، احذف</button>
        </div>
    </div>
</div>

<div id="toasts"></div>

<script src="<?php echo e(asset('js/pos.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xamppp\htdocs\ph\resources\views/layouts/pos.blade.php ENDPATH**/ ?>