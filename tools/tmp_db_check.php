<?php
$dsn = 'pgsql:host=ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech;port=5432;dbname=TARIQ;sslmode=require';
$user = 'neondb_owner';
$pass = 'npg_ykgfTMnI42qV';
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query("select table_name from information_schema.tables where table_schema='public' order by table_name");
    foreach ($stmt as $row) {
        echo $row['table_name'] . PHP_EOL;
    }
    echo "--- constraints ---" . PHP_EOL;
    $stmt = $pdo->query("select table_name, constraint_name, constraint_type from information_schema.table_constraints where table_schema='public' order by table_name, constraint_name");
    foreach ($stmt as $row) {
        echo sprintf('%s %s %s', $row['table_name'], $row['constraint_name'], $row['constraint_type']) . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
