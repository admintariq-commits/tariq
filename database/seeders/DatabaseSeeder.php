<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            RegionSeeder::class,
            UniversitySeeder::class,
            CourseSeeder::class,
            AlertTypeSeeder::class,
            AdminUserSeeder::class,
            MinistryUserSeeder::class,
            RegisteredStudentsSeeder::class,
        ]);
    }
}