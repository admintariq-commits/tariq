<?php
// Direct PDO check - no Laravel needed
$dbUrl = 'postgresql://neondb_owner:npg_ykgfTMnI42qV@ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech/TARIQ?sslmode=require&channel_binding=require';
$parsed = parse_url($dbUrl);
$host = $parsed['host'];
$port = $parsed['port'] ?? 5432;
$database = ltrim($parsed['path'] ?? '', '/');
$user = $parsed['user'];
$pass = $parsed['pass'];

$dsn = "pgsql:host=$host;port=$port;dbname=$database;sslmode=require;options='-c%20endpoint=ep-polished-butterfly-ayqfh2r6'";

try {
    $pdo = new PDO($dsn, $parsed['user'], $parsed['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "✓ Connected to TARIQ database\n\n";
    
    // Get all tables
    $result = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname='public' ORDER BY tablename;");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables Created (" . count($tables) . "):\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    
    // Check migrations
    if (in_array('migrations', $tables)) {
        echo "\n✓ Migrations table exists\n";
        $result = $pdo->query("SELECT COUNT(*) as count FROM migrations;");
        $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
        echo "  Applied migrations: " . $count . "\n";
    }
    
    // Check key tables
    echo "\nKey Tables:\n";
    $keyTables = ['users', 'roles', 'graduates'];
    foreach ($keyTables as $table) {
        if (in_array($table, $tables)) {
            $result = $pdo->query("SELECT COUNT(*) as count FROM \"$table\";");
            $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
            echo "  ✓ $table ($count rows)\n";
        } else {
            echo "  ✗ $table (MISSING)\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
