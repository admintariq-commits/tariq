<?php
require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'driver'    => getenv('DB_CONNECTION') ?: 'mysql',
    'host'      => getenv('DB_HOST') ?: '127.0.0.1',
    'database'  => getenv('DB_DATABASE'),
    'username'  => getenv('DB_USERNAME'),
    'password'  => getenv('DB_PASSWORD'),
    'charset'   => getenv('DB_CHARSET') ?: 'utf8mb4',
    'collation' => getenv('DB_COLLATION') ?: 'utf8mb4_unicode_ci',
    'prefix'    => '',
];

$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$schema = getenv('DB_DATABASE');
$tables = $capsule->select("SELECT TABLE_NAME, ENGINE, TABLE_TYPE, TABLE_ROWS FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?", [$schema]);

$out = "Database: $schema\n";
foreach ($tables as $table) {
    $out .= sprintf("%s | %s | %s | %s\n", $table->TABLE_NAME, $table->ENGINE, $table->TABLE_TYPE, $table->TABLE_ROWS);
}

file_put_contents(__DIR__ . '/temp_db_check_out.txt', $out);
