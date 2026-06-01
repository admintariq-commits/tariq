<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-slate-950 py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-slate-900/95 rounded-[2rem] p-8 shadow-2xl border border-white/10 text-white mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold">Employment Intelligence</h1>
                        <p class="text-sm text-slate-300 mt-2">Run workforce analytics and review ministry-grade employment insights.</p>
                    </div>
                    <a href="<?php echo e(route('ministry.dashboard')); ?>" class="inline-flex items-center justify-center rounded-full bg-indigo-600 text-white px-5 py-2 text-sm font-semibold hover:bg-indigo-700 transition">Back to Ministry Home</a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-4 mb-8">
                <a href="<?php echo e(route('ministry.analytics.employment-trends')); ?>" class="group rounded-[1.75rem] border border-slate-800/70 bg-slate-900/95 p-6 shadow-2xl shadow-slate-900/20 hover:border-indigo-400 transition hover:-translate-y-1">
                    <p class="text-sm uppercase tracking-[0.18em] text-indigo-300">Employment Trends</p>
                    <h2 class="text-2xl font-semibold text-white mt-4">Job status breakdown</h2>
                    <p class="text-slate-400 mt-3">Analyze employment, unemployment duration and time to placement.</p>
                </a>
                <a href="<?php echo e(route('ministry.analytics.salary-analysis')); ?>" class="group rounded-[1.75rem] border border-slate-800/70 bg-slate-900/95 p-6 shadow-2xl shadow-slate-900/20 hover:border-amber-400 transition hover:-translate-y-1">
                    <p class="text-sm uppercase tracking-[0.18em] text-amber-300">Salary Analysis</p>
                    <h2 class="text-2xl font-semibold text-white mt-4">Wage dynamics</h2>
                    <p class="text-slate-400 mt-3">Compare pay expectations and employment salary ranges across regions.</p>
                </a>
                <a href="<?php echo e(route('ministry.analytics.skills-gap')); ?>" class="group rounded-[1.75rem] border border-slate-800/70 bg-slate-900/95 p-6 shadow-2xl shadow-slate-900/20 hover:border-sky-400 transition hover:-translate-y-1">
                    <p class="text-sm uppercase tracking-[0.18em] text-sky-300">Skills Gap</p>
                    <h2 class="text-2xl font-semibold text-white mt-4">Demand vs supply</h2>
                    <p class="text-slate-400 mt-3">See where graduate skill supply diverges from job market demand.</p>
                </a>
                <a href="<?php echo e(route('ministry.analytics.profile-completeness')); ?>" class="group rounded-[1.75rem] border border-slate-800/70 bg-slate-900/95 p-6 shadow-2xl shadow-slate-900/20 hover:border-emerald-400 transition hover:-translate-y-1">
                    <p class="text-sm uppercase tracking-[0.18em] text-emerald-300">Data Quality</p>
                    <h2 class="text-2xl font-semibold text-white mt-4">Profile Completion</h2>
                    <p class="text-slate-400 mt-3">Compare filled graduate profile fields with employment outcome readiness.</p>
                </a>
            </div>

            <div class="bg-slate-900/95 rounded-[1.75rem] p-6 border border-white/10">
                <h2 class="text-xl font-semibold text-white mb-4">Recent Analytics</h2>
                <?php if($reports->isEmpty()): ?>
                    <p class="text-slate-400">No analytics reports have been generated yet. Run an analysis above to view details.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="rounded-3xl bg-slate-800/80 p-4 border border-white/10">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div>
                                        <p class="text-slate-400 text-sm uppercase tracking-[0.16em]"><?php echo e(str_replace('_', ' ', ucfirst($report->type))); ?></p>
                                        <h3 class="text-lg font-semibold text-white mt-1"><?php echo e($report->name); ?></h3>
                                    </div>
                                    <span class="text-slate-400 text-sm"><?php echo e($report->generated_at->format('M d, Y H:i')); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tariq\resources\views/ministry/analytics/dashboard.blade.php ENDPATH**/ ?>