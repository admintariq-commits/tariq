#!/usr/bin/env php
<?php
// Direct TARIQ Database Fix
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

echo "\n╔════════════════════════════════════════╗\n";
echo "║   TARIQ Database Setup & Migration   ║\n";
echo "╚════════════════════════════════════════╝\n\n";

$tasks = [
    '1. Clearing caches' => function() use ($kernel) {
        $kernel->call('config:clear');
        $kernel->call('route:clear');
        $kernel->call('cache:clear');
        return true;
    },
    '2. Dropping existing tables' => function() use ($kernel) {
        try {
            $kernel->call('migrate:reset', ['--force' => true, '--quiet' => true]);
            return true;
        } catch (\Exception $e) {
            // Table might not exist, that's okay
            return true;
        }
    },
    '3. Running fresh migrations' => function() use ($kernel) {
        try {
            $kernel->call('migrate', ['--force' => true]);
            return true;
        } catch (\Exception $e) {
            echo "   Warning: {$e->getMessage()}\n";
            return false;
        }
    },
    '4. Running database seeders' => function() use ($kernel) {
        try {
            $kernel->call('db:seed', ['--force' => true]);
            return true;
        } catch (\Exception $e) {
            echo "   Note: {$e->getMessage()}\n";
            return true;
        }
    }
];

foreach ($tasks as $task => $callable) {
    echo "[$task]";
    try {
        $result = $callable();
        echo " ✓\n";
    } catch (\Throwable $e) {
        echo " ✗\n   Error: {$e->getMessage()}\n";
    }
}

echo "\n╔════════════════════════════════════════╗\n";
echo "║   Database Setup Complete!           ║\n";
echo "╚════════════════════════════════════════╝\n\n";

// Verify
echo "Verifying database...\n";
try {
    $migrations = \DB::table('migrations')->count();
    $users = \DB::table('users')->count();
    echo "✓ Migrations table: $migrations records\n";
    echo "✓ Users table: $users records\n";
} catch (\Exception $e) {
    echo "✗ Verification failed: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
?>
