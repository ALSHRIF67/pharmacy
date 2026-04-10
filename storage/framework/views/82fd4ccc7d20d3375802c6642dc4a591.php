<!-- Invoice Modal (shared, only needed on POS but included globally for simplicity) -->
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
<?php /**PATH C:\xamppp\htdocs\ph\resources\views/pos/components/invoice-modal.blade.php ENDPATH**/ ?>