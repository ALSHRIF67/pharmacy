<?php $__env->startSection('header_title', 'تعديل المنتج'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-700">تعديل: <?php echo e($product->name); ?></h3>
            <a href="<?php echo e(route('products.index')); ?>" class="text-sm text-gray-500 hover:text-emerald-500 flex items-center transition-colors">
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                العودة للقائمة
            </a>
        </div>

        <form action="<?php echo e(route('products.update', $product->id)); ?>" method="POST" class="p-8 space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المنتج</label>
                <input type="text" name="name" id="name" value="<?php echo e(old('name', $product->name)); ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="barcode" class="block text-sm font-medium text-gray-700">الباركود</label>
                    <input type="text" name="barcode" id="barcode" value="<?php echo e(old('barcode', $product->barcode)); ?>" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none font-mono">
                </div>

                <div class="space-y-2">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">القسم</label>
                    <select name="category_id" id="category_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
                        <option value="">بدون قسم</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label for="base_price" class="block text-sm font-medium text-gray-700">السعر الأساسي (بيع)</label>
                <div class="relative">
                    <input type="number" step="0.01" name="base_price" id="base_price" value="<?php echo e(old('base_price', $product->base_price)); ?>" required
                        class="w-full pr-4 pl-12 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                        ر.س
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3 space-x-reverse">
                <a href="<?php echo e(route('products.index')); ?>" class="px-6 py-3 text-gray-500 hover:text-gray-700 font-bold transition-colors">
                    إلغاء
                </a>
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1">
                    تحديث البيانات
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/products/edit.blade.php ENDPATH**/ ?>