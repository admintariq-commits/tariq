

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Welcome back, <?php echo e(Auth::user()->name); ?>!</h1>
                <p class="text-purple-100 mt-1">Here's what's happening with youth employment today.</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold"><?php echo e(date('F j, Y')); ?></div>
                <div class="text-purple-100"><?php echo e(date('l')); ?></div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Graduates</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white"><?php echo e($totalGraduates ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-green-600 text-sm">+12% from last month</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Unemployed</p>
                    <p class="text-3xl font-bold text-red-600"><?php echo e($unemployed ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-frown text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-red-600 text-sm">⚠️ Needs attention</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Employed</p>
                    <p class="text-3xl font-bold text-green-600"><?php echo e($employed ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-smile text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-green-600 text-sm">✅ On track</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Critical (>8 months)</p>
                    <p class="text-3xl font-bold text-orange-600"><?php echo e($critical ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-orange-600 text-sm">🚨 Immediate action required</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Alerts Sent</p>
                    <p class="text-3xl font-bold text-purple-600"><?php echo e($alertsSent ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-purple-600 text-sm">📧 To government</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Average Employability</p>
                    <p class="text-3xl font-bold text-indigo-600"><?php echo e($averageEmployability ?? 0); ?>%</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-indigo-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-indigo-600 text-sm">📊 National readiness index</div>
        </div>
    </div>

    <!-- Academic Verification Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">✅ Verified Records</p>
                    <p class="text-3xl font-bold text-green-600"><?php echo e($verifiedRecords ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">
                <?php 
                    $verifiedPercent = $totalAcademicRecords > 0 ? round(($verifiedRecords / $totalAcademicRecords) * 100) : 0;
                ?>
                <?php echo e($verifiedPercent); ?>% of total
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">⏳ Pending Verification</p>
                    <p class="text-3xl font-bold text-yellow-600"><?php echo e($pendingRecords ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">
                <?php 
                    $pendingPercent = $totalAcademicRecords > 0 ? round(($pendingRecords / $totalAcademicRecords) * 100) : 0;
                ?>
                <?php echo e($pendingPercent); ?>% of total
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">❌ Rejected Records</p>
                    <p class="text-3xl font-bold text-red-600"><?php echo e($rejectedRecords ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">
                <?php 
                    $rejectedPercent = $totalAcademicRecords > 0 ? round(($rejectedRecords / $totalAcademicRecords) * 100) : 0;
                ?>
                <?php echo e($rejectedPercent); ?>% of total
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">👤 Graduates Verified</p>
                    <p class="text-3xl font-bold text-blue-600"><?php echo e($graduatesWithVerification ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">
                <?php 
                    $verifiedGradPercent = $totalGraduates > 0 ? round(($graduatesWithVerification / $totalGraduates) * 100) : 0;
                ?>
                <?php echo e($verifiedGradPercent); ?>% of graduates
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">🚨 At-risk Graduates</p>
                    <p class="text-3xl font-bold text-rose-600"><?php echo e($atRiskGraduates ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-heart-broken text-rose-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">Low employability score, needs support</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Unemployment Trend Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">📈 Unemployment Trend (Last 6 Months)</h3>
                <select class="text-sm border rounded-lg px-2 py-1">
                    <option>Last 6 months</option>
                    <option>Last year</option>
                </select>
            </div>
            <canvas id="unemploymentChart" height="250" data-chart='{"labels":["Jan","Feb","Mar","Apr","May","Jun"],"data":[12,19,15,17,14,<?php echo e($unemployed ?? 0); ?>]}'></canvas>
        </div>

        <!-- Regional Distribution Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">🗺️ Regional Distribution</h3>
            </div>
            <canvas id="regionalChart" height="250" data-chart='{"labels":<?php echo json_encode($regionData->pluck('name')->toArray()); ?>,"data":<?php echo json_encode($regionData->pluck('graduates_count')->toArray()); ?>}'></canvas>
        </div>
    </div>

    <!-- Recent Graduates & Alerts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Graduates -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">👥 Recent Graduates</h3>
                <a href="<?php echo e(route('admin.graduates.index')); ?>" class="text-blue-600 text-sm">View all →</a>
            </div>
            <div class="space-y-3">
                <?php
                    $recentGrads = App\Models\Graduate::with('user')->latest()->take(5)->get();
                ?>
                <?php $__currentLoopData = $recentGrads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white">
                            <?php echo e(substr($grad->first_name, 0, 1)); ?><?php echo e(substr($grad->last_name, 0, 1)); ?>

                        </div>
                        <div>
                            <p class="font-semibold"><?php echo e($grad->first_name); ?> <?php echo e($grad->last_name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($grad->course->name ?? 'No course'); ?></p>
                        </div>
                    </div>
                    <div>
                        <span class="px-2 py-1 rounded-full text-xs 
                            <?php if($grad->employment_status == 'employed'): ?> bg-green-100 text-green-600
                            <?php elseif($grad->employment_status == 'self_employed'): ?> bg-blue-100 text-blue-600
                            <?php else: ?> bg-red-100 text-red-600 <?php endif; ?>">
                            <?php echo e(str_replace('_', ' ', $grad->employment_status)); ?>

                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Recent Alerts -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">🔔 Recent Alerts</h3>
                <a href="<?php echo e(route('admin.alerts.index')); ?>" class="text-blue-600 text-sm">View all →</a>
            </div>
            <div class="space-y-3">
                <?php
                    $recentAlerts = App\Models\Alert::with('graduate')->latest()->take(5)->get();
                ?>
                <?php $__empty_1 = true; $__currentLoopData = $recentAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-bell text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold"><?php echo e($alert->graduate->full_name ?? 'Unknown'); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($alert->type->name ?? 'Alert'); ?></p>
                        </div>
                    </div>
                    <div>
                        <span class="px-2 py-1 rounded-full text-xs 
                            <?php if($alert->status == 'sent'): ?> bg-green-100 text-green-600
                            <?php elseif($alert->status == 'pending'): ?> bg-yellow-100 text-yellow-600
                            <?php else: ?> bg-gray-100 text-gray-600 <?php endif; ?>">
                            <?php echo e($alert->status); ?>

                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-center py-4">No alerts yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script src="<?php echo e(asset('js/admin-dashboard.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tariq\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>