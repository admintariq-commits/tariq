<?php

namespace Tests\Unit;

use Illuminate\Database\Connectors\PostgresConnector;
use Tests\TestCase;

class DatabaseConfigTest extends TestCase
{
    public function test_pgsql_connection_options_is_an_array(): void
    {
        $config = config('database.connections.pgsql');

        $this->assertIsArray($config['options'] ?? null);
    }

    public function test_pgsql_connector_includes_neon_dsn_options(): void
    {
        $config = config('database.connections.pgsql');
        $connector = new PostgresConnector();

        $dsn = $this->invokeProtectedMethod($connector, 'getDsn', [$config]);

        $this->assertStringContainsString('sslmode=require', $dsn);
        $this->assertStringContainsString('options=endpoint=', $dsn);
    }

    protected function invokeProtectedMethod(object $object, string $method, array $args = [])
    {
        $reflection = new \ReflectionMethod($object, $method);
        $reflection->setAccessible(true);

        return $reflection->invokeArgs($object, $args);
    }
}
