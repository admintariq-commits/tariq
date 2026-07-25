<?php
// Verify TARIQ database setup
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer autoloader
require 'vendor/autoload.php';

// Get environment variables
$dbUrl = getenv('DATABASE_URL') ?: 'postgresql://neondb_owner:npg_ykgfTMnI42qV@ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech/TARIQ?sslmode=require&channel_binding=require';

echo "=== TARIQ Database Verification ===\n\n";

// Parse connection string
$parsed = parse_url($dbUrl);
$host = $parsed['host'] ?? 'localhost';
$port = $parsed['port'] ?? 5432;
$db = ltrim($parsed['path'] ?? '', '/');
$user = $parsed['user'] ?? '';
$pass = $parsed['pass'] ?? '';

echo "[1] Connection Details:\n";
echo "  Host: $host\n";
echo "  Port: $port\n";
echo "  Database: $db\n";
echo "  User: $user\n\n";

// Connect using PDO
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "[OK] Connected to TARIQ database\n\n";
    
    // Check for migrations table
    echo "[2] Checking tables:\n";
    $result = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public' ORDER BY table_name;");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "  ERROR: No tables found! Migrations may not have run.\n";
    } else {
        echo "  Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            echo "    - $table\n";
        }
    }
    
    // Check migrations table
    if (in_array('migrations', $tables)) {
        $result = $pdo->query("SELECT COUNT(*) as count FROM migrations;");
        $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
        echo "\n[3] Migrations Status:\n";
        echo "  Migrations table exists with $count applied migrations\n";
    } else {
        echo "\n[3] ERROR: migrations table not found!\n";
    }
    
    echo "\n[OK] Database verification complete!\n";
    
} catch (Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
?>
