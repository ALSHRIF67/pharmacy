<?php $__env->startSection('title', 'تفاصيل الفاتورة'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden p-6 mt-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">تفاصيل الفاتورة #<?php echo e($sale->id); ?></h3>
        <a href="<?php echo e(route('sales.index')); ?>" class="btn btn-w">العودة للسجل</a>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg mb-6 flex justify-between">
        <div><span class="font-bold text-gray-700">التاريخ: </span> <span class="text-gray-600"><?php echo e($sale->created_at->format('Y/m/d H:i')); ?></span></div>
        <div><span class="font-bold text-gray-700">الإجمالي: </span> <span class="text-emerald-700 font-bold"><?php echo e(number_format($sale->total_price, 2)); ?> ر.س</span></div>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="p-3 text-right">المنتج</th>
                <th class="p-3 text-center">الكمية</th>
                <th class="p-3 text-center">السعر</th>
                <th class="p-3 text-center">الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sale->saleItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b">
                    <td class="p-3"><?php echo e($item->product->name ?? 'منتج محذوف'); ?></td>
                    <td class="p-3 text-center"><?php echo e($item->quantity); ?></td>
                    <td class="p-3 text-center"><?php echo e(number_format($item->price, 2)); ?></td>
                    <td class="p-3 text-center font-bold text-emerald-700"><?php echo e(number_format($item->quantity * $item->price, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/sales/show.blade.php ENDPATH**/ ?>