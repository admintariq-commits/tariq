<?php

namespace Tests\Feature;

use Tests\TestCase;

class MigrationTransactionSafetyTest extends TestCase
{
    public function test_initial_schema_migrations_are_non_transactional_for_postgres(): void
    {
        $files = [
            database_path('migrations/2026_05_29_211753_create_roles_table.php'),
            database_path('migrations/2026_05_29_211754_create_users_table.php'),
            database_path('migrations/2026_05_29_211756_create_regions_table.php'),
            database_path('migrations/2026_05_29_211757_create_courses_table.php'),
            database_path('migrations/2026_05_29_211757_create_universities_table.php'),
            database_path('migrations/2026_05_29_211758_create_graduates_table.php'),
            database_path('migrations/2026_05_30_120000_create_settings_table.php'),
        ];

        foreach ($files as $file) {
            $this->assertFileExists($file, $file . ' should exist');
            $contents = file_get_contents($file);
            $this->assertIsString($contents, $file . ' should be readable');
            $this->assertStringContainsString('public $withinTransaction = false;', $contents, $file . ' should disable transaction wrapping');
        }
    }
}
