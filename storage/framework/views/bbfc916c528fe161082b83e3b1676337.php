<?php $__env->startSection('header_title', 'نقطة البيع'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Main POS Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Products Panel (Left) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <!-- Toolbar -->
                <div class="p-4 border-b border-gray-100 flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[180px]">
                        <div class="relative">
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" id="srch" placeholder="بحث بالاسم أو الباركود..." oninput="window.renderGrid()"
                                class="w-full pr-10 pl-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none text-sm">
                        </div>
                    </div>
                    <input type="text" id="barcode-inp" placeholder="📷 باركود + Enter"
                        class="w-48 px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none text-sm font-mono">
                    <button id="qr-btn" onclick="window.toggleQr()"
                        class="bg-amber-100 hover:bg-amber-200 text-amber-700 px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                        📷 مسح QR
                    </button>
                    <button onclick="window.loadProducts()"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors">
                        🔄 تحديث
                    </button>
                </div>

                <!-- QR Reader -->
                <div id="qr-wrap" class="hidden relative bg-black">
                    <button id="qr-x" onclick="window.stopQr()" class="absolute top-2 left-2 bg-black/60 text-white rounded-md px-2 py-1 text-sm z-10">✕</button>
                    <div id="reader"></div>
                </div>

                <!-- Products Grid -->
                <div id="prod-grid" class="p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto">
                    <div class="col-span-full text-center py-12 text-gray-400">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-gray-200 border-t-emerald-500 mb-3"></div>
                        <p>جاري التحميل...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Panel (Right) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-6">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-bold text-gray-800">🛒 السلة</h2>
                    <span id="cart-cnt" class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded-full">0</span>
                </div>
                <div id="cart-body" class="p-3 space-y-2 max-h-[500px] overflow-y-auto">
                    <div class="text-center py-12 text-gray-400">
                        <div class="text-5xl mb-2">🛍️</div>
                        <p>السلة فارغة</p>
                        <p class="text-xs mt-1">انقر منتجاً لإضافته</p>
                    </div>
                </div>
                <div class="p-4 border-t border-gray-100">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-gray-600">الإجمالي</span>
                        <span id="cart-tot" class="text-2xl font-bold text-emerald-600">0.00 ر.س</span>
                    </div>
                    <button id="chk-btn" disabled
                        class="w-full bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1">
                        ✅ إتمام البيع
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales Section -->
    <div class="mt-8 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-700">آخر المبيعات</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجمالي</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($sale->product->name ?? '—'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($sale->quantity); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e(number_format($sale->price, 2)); ?> ر.س</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600"><?php echo e(number_format($sale->total, 2)); ?> ر.س</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($sale->created_at->format('Y-m-d H:i')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">لا توجد مبيعات حتى الآن</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.initPOS === 'function') {
            window.initPOS();
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/products/create.blade.php ENDPATH**/ ?>