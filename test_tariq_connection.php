<?php
// Test TARIQ database connection
$host = 'ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech';
$port = 5432;
$database = 'TARIQ';
$username = 'neondb_owner';
$password = 'npg_ykgfTMnI42qV';

try {
    $dsn = "pgsql:host={$host};port={$port};dbname={$database};sslmode=require";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    ]);
    
    echo "✓ Connection successful to TARIQ database\n";
    
    // Check if migrations table exists
    $stmt = $pdo->query("SELECT EXISTS (SELECT 1 FROM pg_class c, pg_namespace n WHERE n.nspname = current_schema() AND c.relname = 'migrations' AND c.relkind IN ('r', 'p') AND n.oid = c.relnamespace)");
    $exists = $stmt->fetchColumn();
    
    if ($exists) {
        echo "✓ Migrations table exists\n";
    } else {
        echo "✗ Migrations table does NOT exist (needs to be created)\n";
    }
    
    // List all tables
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "✗ No tables found (database is empty)\n";
    } else {
        echo "✓ Tables found:\n";
        foreach ($tables as $table) {
            echo "  - {$table}\n";
        }
    }
    
    exit(0);
} catch (Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
