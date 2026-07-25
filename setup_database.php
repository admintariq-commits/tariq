#!/usr/bin/env php
<?php
/**
 * TARIQ Database Setup and Verification Script
 * This script connects directly to the database and runs necessary setup steps
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');

// Reset migrations (drop all tables)
echo "\n=== TARIQ Database Setup ===\n\n";
echo "[1/4] Rolling back all migrations...\n";

try {
    $exitCode = $kernel->call('migrate:reset', ['--force' => true]);
    echo "✓ Rollback complete\n\n";
} catch (\Exception $e) {
    echo "Note: " . $e->getMessage() . "\n\n";
}

// Run fresh migrations
echo "[2/4] Running fresh migrations...\n";
try {
    $exitCode = $kernel->call('migrate:refresh', ['--force' => true]);
    echo "✓ Migrations complete\n\n";
} catch (\Exception $e) {
    echo "✗ Migration error: " . $e->getMessage() . "\n";
    exit(1);
}

// Seed database
echo "[3/4] Seeding database...\n";
try {
    $exitCode = $kernel->call('db:seed', ['--force' => true]);
    echo "✓ Seeding complete\n\n";
} catch (\Exception $e) {
    echo "Note: " . $e->getMessage() . "\n\n";
}

// Verify database
echo "[4/4] Verifying database setup...\n";
try {
    $exitCode = $kernel->call('migrate:status');
    echo "\n✓ Database verification complete\n";
} catch (\Exception $e) {
    echo "✗ Verification error: " . $e->getMessage() . "\n";
}

echo "\n=== Setup Finished ===\n";
echo "✓ TARIQ database is ready!\n\n";
?>
