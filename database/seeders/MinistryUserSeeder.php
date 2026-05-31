<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MinistryUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ministryRole = Role::firstOrCreate(['name' => 'ministry']);

        User::firstOrCreate(
            ['email' => 'ministry@tariq.go.tz'],
            [
                'name' => 'TARIQ Ministry User',
                'password' => Hash::make('Ministry1234!'),
                'role_id' => $ministryRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
