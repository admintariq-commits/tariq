

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">👥 Graduates List</h1>
        <a href="<?php echo e(route('admin.graduates.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg">+ Add New Graduate</a>
    </div>

    <?php if($graduates->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Phone</th>
                        <th class="px-4 py-2 text-left">Course</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $graduates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b border-gray-200 dark:border-gray-600">
                        <td class="px-4 py-2"><?php echo e($grad->id); ?></td>
                        <td class="px-4 py-2"><?php echo e($grad->first_name); ?> <?php echo e($grad->last_name); ?></td>
                        <td class="px-4 py-2"><?php echo e($grad->user->email ?? 'N/A'); ?></td>
                        <td class="px-4 py-2"><?php echo e($grad->phone); ?></td>
                        <td class="px-4 py-2"><?php echo e($grad->course->name ?? 'N/A'); ?></td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full text-xs 
                                <?php if($grad->employment_status == 'employed'): ?> bg-green-500 text-white
                                <?php elseif($grad->employment_status == 'self_employed'): ?> bg-blue-500 text-white
                                <?php else: ?> bg-red-500 text-white <?php endif; ?>">
                                <?php echo e(str_replace('_', ' ', $grad->employment_status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <form method="POST" action="<?php echo e(route('admin.graduates.destroy', $grad)); ?>" onsubmit="return confirm('Delete this graduate?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-users-slash text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-500">No graduates registered yet.</p>
            <a href="<?php echo e(route('admin.graduates.create')); ?>" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg">Add Your First Graduate</a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tariq\resources\views/admin/graduates.blade.php ENDPATH**/ ?>