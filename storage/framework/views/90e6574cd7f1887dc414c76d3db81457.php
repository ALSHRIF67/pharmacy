<?php $__env->startSection('header_title', 'قائمة المنتجات'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-700">جميع المنتجات</h3>
        <a href="<?php echo e(route('products.create')); ?>" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            إضافة منتج جديد
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-right">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">المنتج</th>
                    <th class="px-6 py-4 font-semibold">الباركود</th>
                    <th class="px-6 py-4 font-semibold">القسم</th>
                    <th class="px-6 py-4 font-semibold">السعر الأساسي</th>
                    <th class="px-6 py-4 font-semibold">الكمية الإجمالية</th>
                    <th class="px-6 py-4 font-semibold text-center">العمليات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900"><?php echo e($product->name); ?></div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?php echo e($product->barcode); ?></span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <?php echo e($product->category->name ?? 'بدون قسم'); ?>

                        </td>
                        <td class="px-6 py-4 text-emerald-600 font-bold">
                            <?php echo e(number_format($product->base_price, 2)); ?> ر.س
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $totalStock = $product->batches->sum(function($batch) {
                                    return $batch->quantity;
                                });
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo e($totalStock < 10 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'); ?>">
                                <?php echo e($totalStock); ?> قطعة
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-2 space-x-reverse">
                            <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="inline-block text-blue-500 hover:text-blue-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                            لا توجد منتجات حالياً
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($products->hasPages()): ?>
        <div class="p-6 border-t border-gray-100">
            <?php echo e($products->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/products/index.blade.php ENDPATH**/ ?>