<?php

namespace Database\Seeders;

use App\Models\CareerResource;
use Illuminate\Database\Seeder;

class CareerResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            [
                'title' => 'Mastering Technical Interviews',
                'description' => 'Comprehensive guide to acing technical interviews for software development roles',
                'type' => 'guide',
                'category' => 'interview_prep',
                'url' => 'https://example.com/tech-interviews',
                'provider' => 'CareerHub',
                'duration_minutes' => 120,
                'difficulty_level' => 'intermediate',
                'is_free' => false,
                'cost' => 50000,
                'rating' => 4.8,
                'is_featured' => true,
                'tags' => ['interview', 'technical', 'programming'],
            ],
            [
                'title' => 'Python for Beginners',
                'description' => 'Learn Python programming from scratch with hands-on exercises',
                'type' => 'course',
                'category' => 'skill_development',
                'url' => 'https://example.com/python-course',
                'provider' => 'Udemy',
                'duration_minutes' => 480,
                'difficulty_level' => 'beginner',
                'is_free' => true,
                'cost' => 0,
                'rating' => 4.6,
                'is_featured' => true,
                'tags' => ['programming', 'python', 'development'],
            ],
            [
                'title' => 'Career Planning 101',
                'description' => 'How to plan your career path effectively',
                'type' => 'article',
                'category' => 'career_planning',
                'url' => 'https://example.com/career-planning',
                'provider' => 'LinkedIn Learning',
                'duration_minutes' => 45,
                'difficulty_level' => 'beginner',
                'is_free' => true,
                'cost' => 0,
                'rating' => 4.3,
                'is_featured' => false,
                'tags' => ['career', 'planning', 'development'],
            ],
            [
                'title' => 'Business Communication Skills',
                'description' => 'Enhance your professional communication skills',
                'type' => 'video',
                'category' => 'professional_development',
                'url' => 'https://example.com/business-communication',
                'provider' => 'YouTube',
                'duration_minutes' => 90,
                'difficulty_level' => 'intermediate',
                'is_free' => true,
                'cost' => 0,
                'rating' => 4.5,
                'is_featured' => true,
                'tags' => ['communication', 'professional', 'skills'],
            ],
            [
                'title' => 'Excel Advanced Training',
                'description' => 'Master Excel with advanced formulas and data analysis',
                'type' => 'course',
                'category' => 'skill_development',
                'url' => 'https://example.com/excel-advanced',
                'provider' => 'Microsoft',
                'duration_minutes' => 300,
                'difficulty_level' => 'advanced',
                'is_free' => false,
                'cost' => 75000,
                'rating' => 4.7,
                'is_featured' => true,
                'tags' => ['excel', 'data', 'productivity'],
            ],
        ];

        foreach ($resources as $resource) {
            CareerResource::create($resource);
        }

        $this->command->info('Career resources seeded successfully!');
    }
}
