<?php $__env->startSection('title', 'إضافة منتج جديد'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 600px; margin: 30px auto; padding: 20px;">
    <h2 style="margin-bottom: 20px;">➕ إضافة منتج جديد</h2>
    <p>لإضافة منتج جديد، يرجى الانتقال إلى صفحة <a href="<?php echo e(route('products.create')); ?>">إضافة منتج</a>.</p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pos', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/pos/create.blade.php ENDPATH**/ ?>