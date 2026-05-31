
<?php $__env->startSection('content'); ?>
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
<h2 class="text-xl font-bold mb-4">Login</h2>
<form method="POST" action="<?php echo e(route('login.post')); ?>">
<?php echo csrf_field(); ?>
<input type="email" name="email" placeholder="Email" class="w-full border p-2 mb-2 rounded">
<input type="password" name="password" placeholder="Password" class="w-full border p-2 mb-2 rounded">
<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Login</button>
</form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tariq\resources\views/auth/login.blade.php ENDPATH**/ ?>