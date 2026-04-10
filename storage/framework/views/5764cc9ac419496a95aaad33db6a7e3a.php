<?php $__env->startSection('header_title', 'تعديل بيانات المورد'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-700">تعديل: <?php echo e($supplier->name); ?></h3>
            <a href="<?php echo e(route('suppliers.index')); ?>" class="text-sm text-gray-500 hover:text-emerald-500 flex items-center transition-colors">
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                العودة للقائمة
            </a>
        </div>

        <form action="<?php echo e(route('suppliers.update', $supplier->id)); ?>" method="POST" class="p-8 space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المورد / الشركة</label>
                <input type="text" name="name" id="name" value="<?php echo e(old('name', $supplier->name)); ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
            </div>

            <div class="space-y-2">
                <label for="contact_person" class="block text-sm font-medium text-gray-700">الشخص المسؤول</label>
                <input type="text" name="contact_person" id="contact_person" value="<?php echo e(old('contact_person', $supplier->contact_person)); ?>"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
            </div>

            <div class="space-y-2">
                <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                <input type="text" name="phone" id="phone" value="<?php echo e(old('phone', $supplier->phone)); ?>"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end space-x-3 space-x-reverse">
                <a href="<?php echo e(route('suppliers.index')); ?>" class="px-6 py-3 text-gray-500 hover:text-gray-700 font-bold transition-colors">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xamppp\htdocs\ph\resources\views/suppliers/edit.blade.php ENDPATH**/ ?>