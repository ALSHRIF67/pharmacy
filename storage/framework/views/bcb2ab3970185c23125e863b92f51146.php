<form method="POST" action="<?php echo e($action); ?>" id="product-form">
    <?php echo csrf_field(); ?>
    <?php if($method === 'PUT'): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="fg">
        <label>الباركود *</label>
        <input type="text" name="barcode" id="ef-bc" value="<?php echo e(old('barcode', $product->barcode ?? '')); ?>" required>
        <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="ferr" style="display:block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="fg">
        <label>اسم المنتج *</label>
        <input type="text" name="name" id="ef-nm" value="<?php echo e(old('name', $product->name ?? '')); ?>" required>
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="ferr" style="display:block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="g2">
        <div class="fg">
            <label>السعر (ر.س) *</label>
            <input type="number" name="price" step="0.01" min="0.01" value="<?php echo e(old('price', $product->price ?? '')); ?>" required>
        </div>
        <div class="fg">
            <label>المخزون</label>
            <input type="number" name="stock" value="<?php echo e(old('stock', $product->stock ?? 0)); ?>" min="0">
        </div>
    </div>

    <div class="g2">
        <div class="fg">
            <label>رقم الدفعة *</label>
            <input type="text" name="batch" value="<?php echo e(old('batch', $product->batch ?? '')); ?>" required>
        </div>
        <div class="fg">
            <label>تاريخ الانتهاء *</label>
            <input type="date" name="expiry" value="<?php echo e(old('expiry', $product->expiry ?? '')); ?>" required>
        </div>
    </div>

    <div class="fg">
        <label>ملاحظات</label>
        <input type="text" name="notes" value="<?php echo e(old('notes', $product->notes ?? '')); ?>">
    </div>

    <button type="submit" class="btn btn-p" style="width:100%; padding:12px; font-size:14px;">
        💾 حفظ المنتج
    </button>
</form>
<?php /**PATH C:\xamppp\htdocs\ph\resources\views/pos/components/product-form.blade.php ENDPATH**/ ?>