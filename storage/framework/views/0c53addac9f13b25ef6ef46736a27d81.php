<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-slate-950 py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-slate-900/95 rounded-[2rem] p-8 shadow-2xl border border-white/10 text-white mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold">Ministry Alerts</h1>
                        <p class="text-sm text-slate-300 mt-2">View intervention alerts and monitor workforce risk signals for Tanzania.</p>
                    </div>
                    <a href="<?php echo e(route('ministry.dashboard')); ?>" class="inline-flex items-center justify-center rounded-full bg-indigo-600 text-white px-5 py-2 text-sm font-semibold hover:bg-indigo-700 transition">Back to Dashboard</a>
                </div>
            </div>

            <?php if($alerts->isEmpty()): ?>
                <div class="rounded-3xl bg-slate-900/95 p-8 text-center text-slate-300 border border-white/10">No ministry alerts have been generated yet.</div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-3xl border border-white/10 bg-slate-900/95 p-6 shadow-2xl shadow-slate-900/20">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.18em] text-slate-400"><?php echo e($alert->type->name ?? 'Alert'); ?></p>
                                    <h2 class="text-xl font-semibold text-white mt-2"><?php echo e($alert->title); ?></h2>
                                    <p class="text-sm text-slate-300 mt-3"><?php echo e($alert->message); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-400"><?php echo e($alert->created_at->format('M d, Y H:i')); ?></p>
                                    <span class="inline-flex items-center rounded-full bg-amber-100 text-amber-800 px-3 py-1 text-xs uppercase tracking-[0.16em] mt-2"><?php echo e($alert->status); ?></span>
                                </div>
                            </div>
                            <?php if($alert->graduate): ?>
                                <div class="mt-4 border-t border-white/10 pt-4 text-sm text-slate-300">
                                    <p><strong>Graduate:</strong> <?php echo e($alert->graduate->full_name); ?></p>
                                    <p><strong>Region:</strong> <?php echo e($alert->graduate->region ?? 'N/A'); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tariq\resources\views/ministry/alerts.blade.php ENDPATH**/ ?>