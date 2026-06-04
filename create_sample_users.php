<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;

$graduateRole = Role::firstOrCreate(['name' => 'graduate']);

$samples = [
    [
        'name' => 'John Nkosi',
        'email' => 'john.nkosi@example.com',
        'password' => 'Graduate123!',
    ],
    [
        'name' => 'Amina Hassan',
        'email' => 'amina.hassan@example.com',
        'password' => 'Graduate123!',
    ],
    [
        'name' => 'Peter Mwangi',
        'email' => 'peter.mwangi@example.com',
        'password' => 'Graduate123!',
    ],
    [
        'name' => 'Fatima Suleiman',
        'email' => 'fatima.suleiman@example.com',
        'password' => 'Graduate123!',
    ],
    [
        'name' => 'David Kimani',
        'email' => 'david.kimani@example.com',
        'password' => 'Graduate123!',
    ],
    [
        'name' => 'Grace Njoroge',
        'email' => 'grace.njoroge@example.com',
        'password' => 'Graduate123!',
    ],
    [
        'name' => 'Samuel Chombo',
        'email' => 'samuel.chombo@example.com',
        'password' => 'Graduate123!',
    ],
    [
        'name' => 'Asha Mwakyusa',
        'email' => 'asha.mwakyusa@example.com',
        'password' => 'Graduate123!',
    ],
    [
        'name' => 'Michael Otieno',
        'email' => 'michael.otieno@example.com',
        'password' => 'Graduate123!',
    ],
    [
        'name' => 'Rebecca Mtei',
        'email' => 'rebecca.mtei@example.com',
        'password' => 'Graduate123!',
    ],
];

foreach ($samples as $data) {
    $user = User::firstOrCreate(
        ['email' => $data['email']],
        [
            'name' => $data['name'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT, ['rounds' => 12]),
            'role_id' => $graduateRole->id,
            'email_verified_at' => now(),
        ]
    );
    echo "✓ Created/Updated: {$user->name} ({$user->email})\n";
}

echo "\n=== Sample Users Created ===\n";
echo "Email: john.nkosi@example.com | Password: Graduate123!\n";
echo "Email: amina.hassan@example.com | Password: Graduate123!\n";
echo "Email: peter.mwangi@example.com | Password: Graduate123!\n";
echo "Email: fatima.suleiman@example.com | Password: Graduate123!\n";
echo "Email: david.kimani@example.com | Password: Graduate123!\n";
echo "Email: grace.njoroge@example.com | Password: Graduate123!\n";
echo "Email: samuel.chombo@example.com | Password: Graduate123!\n";
echo "Email: asha.mwakyusa@example.com | Password: Graduate123!\n";
echo "Email: michael.otieno@example.com | Password: Graduate123!\n";
echo "Email: rebecca.mtei@example.com | Password: Graduate123!\n";
?>
