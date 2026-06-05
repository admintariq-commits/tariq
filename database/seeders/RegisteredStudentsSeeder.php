<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Graduate;
use App\Models\Role;
use App\Models\University;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class RegisteredStudentsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $graduateRole = Role::firstOrCreate(['name' => 'graduate']);

        $universities = University::pluck('id')->toArray();
        $courses = Course::pluck('id')->toArray();

        if (empty($universities) || empty($courses)) {
            $this->command->warn('No universities or courses found - skipping graduate seeding.');
            return;
        }

        for ($i = 1; $i <= 120; $i++) {
            $first = $faker->firstName;
            $last = $faker->lastName;

            $user = User::create([
                'name' => $first . ' ' . $last,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'role_id' => $graduateRole->id,
            ]);

            Graduate::create([
                'user_id' => $user->id,
                'first_name' => $first,
                'last_name' => $last,
                'graduation_date' => $faker->dateTimeBetween('-8 years', 'now')->format('Y-m-d'),
                'university_id' => $faker->randomElement($universities),
                'course_id' => $faker->randomElement($courses),
                'gpa' => $faker->randomFloat(2, 2.0, 4.0),
                'phone' => $faker->phoneNumber,
                'employment_status' => $faker->randomElement(['employed', 'self_employed', 'unemployed']),
                'graduation_year' => $faker->year(),
                'region' => $faker->randomElement(['Dar es Salaam', 'Mwanza', 'Arusha', 'Kilimanjaro', 'Dodoma']),
            ]);
        }

        $this->command->info('Inserted 120 sample graduates.');
    }
}
