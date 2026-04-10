<div id="cart-panel">
    <div class="cart-hdr">
        <h2>🛒 السلة</h2>
        <span id="cart-cnt">0</span>
    </div>

    <div id="cart-body">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="text-right">المنتج</th>
                    <th class="text-center">الكمية</th>
                    <th class="text-center">السعر</th>
                    <th class="text-center">المجموع</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="cart-items">
                <!-- JS will render cart rows here -->
            </tbody>
        </table>
        <div id="cart-empty" class="mt-4 text-center text-muted">
            السلة فارغة — انقر منتجاً لإضافته
        </div>
    </div>

    <div class="cart-foot">
        <div class="tot-row">
            <span class="tot-lbl">الإجمالي</span>
            <span id="cart-tot">0.00 ر.س</span>
        </div>
    </div>        
    <button id="chk-btn" class="btn btn-p" disabled> إتمام البيع</button>
</div>
