<?php
// Direct database check
try {
    $dbUrl = 'postgresql://neondb_owner:npg_ykgfTMnI42qV@ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech/TARIQ?sslmode=require&channel_binding=require';
    $parsed = parse_url($dbUrl);
    $dsn = "pgsql:host={$parsed['host']};port={$parsed['port']};dbname=" . ltrim($parsed['path'], '/');
    
    $pdo = new PDO($dsn, $parsed['user'], $parsed['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "Connected!\n";
    
    // List tables
    $result = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname='public';");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . json_encode($tables) . "\n";
    
    // Check migrations
    if (in_array('migrations', $tables)) {
        $result = $pdo->query("SELECT * FROM migrations ORDER BY batch DESC LIMIT 5;");
        $migrations = $result->fetchAll(PDO::FETCH_ASSOC);
        echo "Last 5 migrations:\n";
        foreach ($migrations as $m) {
            echo "  " . $m['migration'] . " (batch: " . $m['batch'] . ")\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
