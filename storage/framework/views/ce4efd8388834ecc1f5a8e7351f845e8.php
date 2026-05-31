

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 sm:px-6">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-6 border-b border-slate-200">
            <h1 class="text-3xl font-bold">⚙️ System Settings</h1>
            <p class="text-sm text-slate-500 mt-2">Configure system, SMTP and security settings for the TARIQ platform.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 p-6 bg-slate-50">
            <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                <h2 class="text-xl font-semibold mb-4">General Settings</h2>
                <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">System Name</label>
                            <input type="text" name="system_name" value="<?php echo e(old('system_name', $settings['system_name'] ?? '')); ?>" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">System Email</label>
                            <input type="email" name="system_email" value="<?php echo e(old('system_email', $settings['system_email'] ?? '')); ?>" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alert Threshold (hours)</label>
                            <input type="number" name="alert_threshold" value="<?php echo e(old('alert_threshold', $settings['alert_threshold'] ?? '')); ?>" min="1" max="24" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Items Per Page</label>
                            <select name="items_per_page" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2">
                                <?php $__currentLoopData = [10,25,50,100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($n); ?>" <?php echo e((old('items_per_page', $settings['items_per_page'] ?? '') == $n) ? 'selected' : ''); ?>><?php echo e($n); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Mail Configuration</h2>
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">From Email Address</label>
                            <input type="email" name="mail_from_address" value="<?php echo e(old('mail_from_address', $settings['mail_from_address'] ?? '')); ?>" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">From Name</label>
                            <input type="text" name="mail_from_name" value="<?php echo e(old('mail_from_name', $settings['mail_from_name'] ?? '')); ?>" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                            <input type="text" name="smtp_host" value="<?php echo e(old('smtp_host', $settings['smtp_host'] ?? '')); ?>" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                                <input type="number" name="smtp_port" value="<?php echo e(old('smtp_port', $settings['smtp_port'] ?? '')); ?>" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Encryption</label>
                                <select name="smtp_encryption" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2">
                                    <?php $__currentLoopData = ['none' => 'None', 'tls' => 'TLS', 'ssl' => 'SSL']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e((old('smtp_encryption', $settings['smtp_encryption'] ?? '') == $key) ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP Username</label>
                            <input type="text" name="smtp_username" value="<?php echo e(old('smtp_username', $settings['smtp_username'] ?? '')); ?>" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP Password</label>
                            <input type="password" name="smtp_password" value="<?php echo e(old('smtp_password', $settings['smtp_password'] ?? '')); ?>" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SMTP Timeout (seconds)</label>
                            <input type="number" name="smtp_timeout" value="<?php echo e(old('smtp_timeout', $settings['smtp_timeout'] ?? '')); ?>" min="1" max="120" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-xl font-semibold mb-4">Security Settings</h2>
                    <div class="grid gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Session Timeout (minutes)</label>
                                <input type="number" name="session_timeout_minutes" value="<?php echo e(old('session_timeout_minutes', $settings['session_timeout_minutes'] ?? '')); ?>" min="5" max="1440" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Min Password Length</label>
                                <input type="number" name="password_min_length" value="<?php echo e(old('password_min_length', $settings['password_min_length'] ?? '')); ?>" min="6" max="64" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="require_strong_passwords" value="0" />
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="require_strong_passwords" value="1" <?php echo e(old('require_strong_passwords', $settings['require_strong_passwords'] ?? false) ? 'checked' : ''); ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                                Require strong passwords
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-6 border-t border-slate-200 bg-white">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-3 rounded-full font-semibold hover:bg-blue-700 transition">Save Settings</button>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Cancel</a>
                </div>
                <div class="text-sm text-slate-500">System settings are stored securely and applied immediately.</div>
            </div>
        </div>
    </form>

    <form method="POST" action="<?php echo e(route('admin.settings.test-mail')); ?>" class="mt-4 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <?php echo csrf_field(); ?>
        <h2 class="text-xl font-semibold mb-4">Send Test Email</h2>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Recipient Email</label>
                <input type="email" name="test_email" value="<?php echo e(old('test_email', $settings['system_email'] ?? '')); ?>" class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-2" />
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-green-600 text-white px-5 py-3 rounded-full font-semibold hover:bg-green-700 transition">Send Test Email</button>
            </div>
        </div>
    </form>

    <?php if(session('success')): ?>
        <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
</div>

            <div>
                <label class="block text-sm font-medium text-gray-700">System Email</label>
                <input type="email" name="system_email" value="<?php echo e(old('system_email', $settings['system_email'] ?? '')); ?>" class="mt-1 block w-full rounded-md border-gray-300" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Alert Threshold (hours)</label>
                <input type="number" name="alert_threshold" value="<?php echo e(old('alert_threshold', $settings['alert_threshold'] ?? '')); ?>" min="1" max="24" class="mt-1 block w-full rounded-md border-gray-300" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Items Per Page</label>
                <select name="items_per_page" class="mt-1 block w-full rounded-md border-gray-300">
                    <?php $__currentLoopData = [10,25,50,100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($n); ?>" <?php echo e((old('items_per_page', $settings['items_per_page'] ?? '') == $n) ? 'selected' : ''); ?>><?php echo e($n); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <h2 class="text-lg font-semibold mb-3">Mail Configuration</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">From Email Address</label>
                <input type="email" name="mail_from_address" value="<?php echo e(old('mail_from_address', $settings['mail_from_address'] ?? '')); ?>" class="mt-1 block w-full rounded-md border-gray-300" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">From Name</label>
                <input type="text" name="mail_from_name" value="<?php echo e(old('mail_from_name', $settings['mail_from_name'] ?? '')); ?>" class="mt-1 block w-full rounded-md border-gray-300" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                <input type="text" name="smtp_host" value="<?php echo e(old('smtp_host', $settings['smtp_host'] ?? '')); ?>" class="mt-1 block w-full rounded-md border-gray-300" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                    <input type="number" name="smtp_port" value="<?php echo e(old('smtp_port', $settings['smtp_port'] ?? '')); ?>" class="mt-1 block w-full rounded-md border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Encryption</label>
                    <select name="smtp_encryption" class="mt-1 block w-full rounded-md border-gray-300">
                        <?php $__currentLoopData = ['none' => 'None', 'tls' => 'TLS', 'ssl' => 'SSL']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e((old('smtp_encryption', $settings['smtp_encryption'] ?? '') == $key) ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">SMTP Username</label>
                <input type="text" name="smtp_username" value="<?php echo e(old('smtp_username', $settings['smtp_username'] ?? '')); ?>" class="mt-1 block w-full rounded-md border-gray-300" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">SMTP Password</label>
                <input type="password" name="smtp_password" value="<?php echo e(old('smtp_password', $settings['smtp_password'] ?? '')); ?>" class="mt-1 block w-full rounded-md border-gray-300" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">SMTP Timeout (seconds)</label>
                <input type="number" name="smtp_timeout" value="<?php echo e(old('smtp_timeout', $settings['smtp_timeout'] ?? '')); ?>" min="1" max="120" class="mt-1 block w-full rounded-md border-gray-300" />
            </div>

            <div class="pt-4 border-t border-gray-200">
                <h2 class="text-lg font-semibold mb-3">Security Settings</h2>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Session Timeout (minutes)</label>
                    <input type="number" name="session_timeout_minutes" value="<?php echo e(old('session_timeout_minutes', $settings['session_timeout_minutes'] ?? '')); ?>" min="5" max="1440" class="mt-1 block w-full rounded-md border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Min Password Length</label>
                    <input type="number" name="password_min_length" value="<?php echo e(old('password_min_length', $settings['password_min_length'] ?? '')); ?>" min="6" max="64" class="mt-1 block w-full rounded-md border-gray-300" />
                </div>
            </div>

            <div class="flex items-center gap-3 mt-2">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="hidden" name="require_strong_passwords" value="0" />
                    <input type="checkbox" name="require_strong_passwords" value="1" <?php echo e(old('require_strong_passwords', $settings['require_strong_passwords'] ?? false) ? 'checked' : ''); ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded" />
                    Require strong passwords
                </label>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Save Settings</button>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-4 py-2 rounded-lg border">Cancel</a>
            </div>
        </div>
    </form>

    <form method="POST" action="<?php echo e(route('admin.settings.test-mail')); ?>" class="mt-8 bg-slate-50 p-6 rounded-xl border border-slate-200">
        <?php echo csrf_field(); ?>
        <h2 class="text-lg font-semibold mb-4">Send Test Email</h2>

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Recipient Email</label>
                <input type="email" name="test_email" value="<?php echo e(old('test_email', $settings['system_email'] ?? '')); ?>" class="mt-1 block w-full rounded-md border-gray-300" />
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Send Test Email</button>
        </div>
    </form>

    <?php if(session('success')): ?>
        <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tariq\resources\views/admin/settings.blade.php ENDPATH**/ ?>