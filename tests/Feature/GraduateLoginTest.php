<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GraduateLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_graduate_credentials_can_login(): void
    {
        $role = Role::create(['name' => 'graduate']);

        $user = User::create([
            'name' => 'Test Graduate',
            'email' => 'graduate@example.com',
            'password' => Hash::make('StrongPass123!'),
            'role_id' => $role->id,
        ]);

        $this->assertTrue(Auth::attempt([
            'email' => $user->email,
            'password' => 'StrongPass123!',
        ]));

        $this->assertSame($user->email, Auth::user()->email);
    }
}
