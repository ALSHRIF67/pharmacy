<?php $__env->startSection('title', 'تعديل المنتج'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-700">تعديل المنتج: <?php echo e($product->name); ?></h3>
    </div>

    <div class="p-8">
        <?php echo $__env->make('pos.components.product-form', [
            'action' => route('pos.update', $product->id),
            'method' => 'PUT',
            'product' => $product
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/pos/edit.blade.php ENDPATH**/ ?>