<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel');

// Test database connection
try {
    $count = \DB::table('migrations')->count();
    $tables = \DB::connection()->getDoctrineSchemaManager()->listTableNames();
    
    echo "✓ Connected to TARIQ database\n";
    echo "\nMigrations Applied: " . $count . "\n";
    echo "\nTables Created (" . count($tables) . "):\n";
    
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    
    // Check key tables
    echo "\nKey Tables Status:\n";
    $keyTables = ['users', 'roles', 'graduates', 'universities', 'courses'];
    foreach ($keyTables as $table) {
        if (in_array($table, $tables)) {
            $rowCount = \DB::table($table)->count();
            echo "  ✓ $table ($rowCount rows)\n";
        } else {
            echo "  ✗ $table (missing)\n";
        }
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
