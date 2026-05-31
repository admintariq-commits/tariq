

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">🚨 Government Alerts</h1>
        <span class="text-sm text-gray-500">Total Alerts: <?php echo e($alerts->count()); ?></span>
    </div>

    <?php if($alerts->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-600">
                    <tr>
                        <th>ID</th><th>Graduate</th><th>Region</th><th>Alert Type</th><th>Status</th><th>Created At</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($alert->id); ?></td>
                        <td><?php echo e($alert->graduate->full_name ?? 'N/A'); ?></td>
                        <td><?php echo e($alert->graduate->region ?? 'N/A'); ?></td>
                        <td><?php echo e($alert->type->name ?? 'N/A'); ?></td>
                        <td><span class="px-2 py-1 rounded-full text-xs bg-gray-500 text-white"><?php echo e($alert->status); ?></span></td>
                        <td><?php echo e($alert->created_at->format('Y-m-d H:i')); ?></td>
                        <td><?php if($alert->status == 'pending'): ?><form method="POST" action="<?php echo e(route('admin.alerts.send', $alert)); ?>"><?php echo csrf_field(); ?><button type="submit">Send</button></form><?php endif; ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No alerts yet.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tariq\resources\views/admin/alerts.blade.php ENDPATH**/ ?>