#!/usr/bin/env php
<?php
// Test database connection via Laravel
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

echo "Testing TARIQ Database Connection...\n\n";

try {
    // Get the database connection
    $connection = \Illuminate\Support\Facades\DB::connection();
    
    // Test connection
    $result = $connection->select('SELECT 1 as test');
    echo "✓ Connected to TARIQ database\n\n";
    
    // Get all tables
    $tables = $connection->getDoctrineSchemaManager()->listTableNames();
    echo "Tables Created (" . count($tables) . "):\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    
    // Check migrations
    if (in_array('migrations', $tables)) {
        echo "\n✓ Migrations table exists\n";
        $count = $connection->table('migrations')->count();
        echo "  Applied migrations: $count\n";
        
        // Show recent migrations
        $migrations = $connection->table('migrations')->orderBy('batch', 'desc')->limit(5)->get();
        echo "\n  Recent migrations:\n";
        foreach ($migrations as $m) {
            echo "    - " . $m->migration . " (batch: " . $m->batch . ")\n";
        }
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✓ Database verification complete!\n";
?>
