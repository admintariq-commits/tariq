<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'josiahmarco93@gmail.com';
$password = 'princeadmin123!';
$user = App\Models\User::where('email', $email)->first();

if (! $user) {
    echo "NOT_FOUND\n";
    exit(1);
}

$user->password = Illuminate\Support\Facades\Hash::make($password);
$user->save();
echo "UPDATED {$user->id} {$user->email}\n";
