<?php $__env->startSection('header_title', 'التقارير والإحصائيات'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Top Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border-r-4 border-emerald-500">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">مبيعات اليوم</div>
            <div class="text-3xl font-black text-emerald-600"><?php echo e(number_format($todaySales, 2)); ?> ر.س</div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border-r-4 border-blue-500">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">مبيعات الشهر</div>
            <div class="text-3xl font-black text-blue-600"><?php echo e(number_format($monthSales, 2)); ?> ر.س</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-r-4 border-amber-500">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">إجمالي المشتريات</div>
            <div class="text-3xl font-black text-amber-600"><?php echo e(number_format($totalPurchases, 2)); ?> ر.س</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Selling -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-700">الأصناف الأكثر مبيعاً</h3>
            </div>
            <div class="p-0">
                <table class="w-full text-right">
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-700"><?php echo e($product->name); ?></td>
                                <td class="px-6 py-4 text-left font-bold text-emerald-600"><?php echo e($product->total_sold); ?> وحدة</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stock Alerts -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-red-100">
            <div class="p-6 border-b border-red-50 bg-red-50/50">
                <h3 class="font-bold text-red-700 flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    تنبيهات المخزون المنخفض
                </h3>
            </div>
            <div class="p-0">
                <table class="w-full text-right">
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $activeBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 font-medium"><?php echo e($batch->product->name); ?></div>
                                    <div class="text-xs text-gray-400 font-mono">تشغيلة: <?php echo e($batch->batch_number); ?></div>
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <span class="text-red-600 font-black"><?php echo e($batch->quantity); ?></span>
                                    <span class="text-xs text-gray-400">متبقي</span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($activeBatches->isEmpty()): ?>
                            <tr>
                                <td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">لا توجد تنبيهات حالياً</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form method="GET" action="<?php echo e(route('reports.index')); ?>" class="mb-6">
        <div class="flex space-x-4">
            <div>
                <label for="from" class="block text-sm font-medium text-gray-700">من</label>
                <input type="date" id="from" name="from" class="w-full px-4 py-3 rounded-lg border border-gray-200">
            </div>
            <div>
                <label for="to" class="block text-sm font-medium text-gray-700">إلى</label>
                <input type="date" id="to" name="to" class="w-full px-4 py-3 rounded-lg border border-gray-200">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl">فلترة</button>
            </div>
        </div>
    </form>

    <h2 class="text-xl font-bold mb-4">الفواتير</h2>
    <table class="w-full border-collapse">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-sm font-medium text-gray-700">رقم الفاتورة</th>
                <th class="p-3 text-sm font-medium text-gray-700">الإجمالي</th>
                <th class="p-3 text-sm font-medium text-gray-700">التاريخ</th>
                <th class="p-3 text-sm font-medium text-gray-700">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b">
                    <td class="p-3 text-sm text-gray-700"><?php echo e($sale->id); ?></td>
                    <td class="p-3 text-sm text-emerald-600 font-bold"><?php echo e(number_format($sale->total_price, 2)); ?></td>
                    <td class="p-3 text-sm text-gray-700"><?php echo e($sale->created_at); ?></td>
                    <td class="p-3 text-sm">
                        <a href="<?php echo e(route('sales.show', $sale->id)); ?>" class="text-blue-500 hover:underline">عرض</a>
                        <a href="<?php echo e(route('sales.edit', $sale->id)); ?>" class="text-yellow-500 hover:underline">تعديل</a>
                        <form action="<?php echo e(route('sales.destroy', $sale->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-500 hover:underline">حذف</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/reports/index.blade.php ENDPATH**/ ?>