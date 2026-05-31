

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <h1 class="text-2xl font-bold mb-4">➕ Add New Graduate</h1>

    <form method="POST" action="<?php echo e(route('admin.graduates.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 mb-2">First Name *</label>
                <input type="text" name="first_name" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Last Name *</label>
                <input type="text" name="last_name" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Email *</label>
                <input type="email" name="email" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Phone *</label>
                <input type="text" name="phone" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Graduation Date *</label>
                <input type="date" name="graduation_date" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">University *</label>
                <select name="university_id" required class="w-full border rounded-lg p-2">
                    <?php $__currentLoopData = $universities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($uni->id); ?>"><?php echo e($uni->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Course *</label>
                <select name="course_id" required class="w-full border rounded-lg p-2">
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>"><?php echo e($course->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Employment Status *</label>
                <select name="employment_status" required class="w-full border rounded-lg p-2">
                    <option value="employed">Employed</option>
                    <option value="self_employed">Self Employed</option>
                    <option value="unemployed">Unemployed</option>
                </select>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Save Graduate</button>
            <a href="<?php echo e(route('admin.graduates.index')); ?>" class="ml-2 text-gray-500">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tariq\resources\views/admin/graduates-create.blade.php ENDPATH**/ ?>