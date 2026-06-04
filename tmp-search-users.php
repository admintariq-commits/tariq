<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::where('email', 'like', '%admin%')
    ->orWhere('email', 'like', '%tariq%')
    ->get();

foreach ($users as $u) {
    echo "{$u->id} | {$u->name} | {$u->email}\n";
}
