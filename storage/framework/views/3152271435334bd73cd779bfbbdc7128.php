<?php $__env->startSection('title', 'إدارة المنتجات'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-700">إدارة المنتجات</h3>
        <div class="flex space-x-4">
            <input type="text" id="tsrch" placeholder="بحث في المنتجات..."
                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
            <a href="<?php echo e(route('pos.create')); ?>" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-200 transition-all hover:-translate-y-1">
                + منتج جديد
            </a>
        </div>
    </div>

    <div class="p-8">
        <?php echo $__env->make('pos.components.product-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.loadProducts === 'function') {
            window.loadProducts();
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/pos/table.blade.php ENDPATH**/ ?>