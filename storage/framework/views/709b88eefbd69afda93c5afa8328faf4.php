<?php $__env->startSection('title', 'سجل الفواتير'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden p-6 mt-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">سجل الفواتير (المبيعات)</h3>
        <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-g">العودة لنقطة البيع</a>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="p-3 text-right">رقم الفاتورة</th>
                <th class="p-3 text-right">عدد المنتجات</th>
                <th class="p-3 text-right">الإجمالي</th>
                <th class="p-3 text-right">التاريخ</th>
                <th class="p-3 text-center">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">#<?php echo e($sale->id); ?></td>
                    <td class="p-3"><?php echo e($sale->saleItems->sum('quantity')); ?></td>
                    <td class="p-3 font-bold text-emerald-700"><?php echo e(number_format($sale->total_price, 2)); ?> ر.س</td>
                    <td class="p-3 text-gray-600"><?php echo e($sale->created_at->format('Y/m/d H:i')); ?></td>
                    <td class="p-3 text-center">
                        <a href="<?php echo e(route('sales.show', $sale->id)); ?>" class="btn btn-w btn-sm">👁 التفاصيل</a>
                        <form action="<?php echo e(route('sales.destroy', $sale->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفاتورة؟');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-d btn-sm">🗑 حذف</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">لا توجد فواتير مسجلة</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/sales/index.blade.php ENDPATH**/ ?>