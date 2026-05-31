<?php

namespace Database\Seeders;

use App\Models\JobMarketTrend;
use Illuminate\Database\Seeder;

class JobMarketTrendSeeder extends Seeder
{
    public function run(): void
    {
        $trends = [
            [
                'industry' => 'Technology',
                'job_title' => 'Software Developer',
                'region' => 'Dar es Salaam',
                'demand_level' => 'high',
                'average_salary' => 3500000,
                'salary_range_min' => 2500000,
                'salary_range_max' => 5000000,
                'required_skills' => ['PHP', 'Laravel', 'JavaScript', 'React'],
                'vacancy_count' => 45,
                'trending_up' => true,
                'trend_percentage' => 15.5,
                'last_updated' => now(),
            ],
            [
                'industry' => 'Finance',
                'job_title' => 'Accountant',
                'region' => 'Dar es Salaam',
                'demand_level' => 'high',
                'average_salary' => 2800000,
                'salary_range_min' => 2000000,
                'salary_range_max' => 4000000,
                'required_skills' => ['IFRS', 'Excel', 'Accounting Software'],
                'vacancy_count' => 32,
                'trending_up' => false,
                'trend_percentage' => -5.2,
                'last_updated' => now(),
            ],
            [
                'industry' => 'Healthcare',
                'job_title' => 'Nurse',
                'region' => 'Dar es Salaam',
                'demand_level' => 'high',
                'average_salary' => 1800000,
                'salary_range_min' => 1500000,
                'salary_range_max' => 2500000,
                'required_skills' => ['Patient Care', 'Medical Knowledge', 'Communication'],
                'vacancy_count' => 58,
                'trending_up' => true,
                'trend_percentage' => 22.3,
                'last_updated' => now(),
            ],
            [
                'industry' => 'Marketing',
                'job_title' => 'Digital Marketing Executive',
                'region' => 'Dar es Salaam',
                'demand_level' => 'medium',
                'average_salary' => 2500000,
                'salary_range_min' => 1800000,
                'salary_range_max' => 3500000,
                'required_skills' => ['Social Media', 'SEO', 'Content Marketing', 'Analytics'],
                'vacancy_count' => 25,
                'trending_up' => true,
                'trend_percentage' => 18.7,
                'last_updated' => now(),
            ],
            [
                'industry' => 'Education',
                'job_title' => 'Teacher',
                'region' => 'Dar es Salaam',
                'demand_level' => 'medium',
                'average_salary' => 1200000,
                'salary_range_min' => 1000000,
                'salary_range_max' => 1800000,
                'required_skills' => ['Pedagogy', 'Subject Matter', 'Communication'],
                'vacancy_count' => 40,
                'trending_up' => false,
                'trend_percentage' => -3.1,
                'last_updated' => now(),
            ],
        ];

        foreach ($trends as $trend) {
            JobMarketTrend::create($trend);
        }

        $this->command->info('Job market trends seeded successfully!');
    }
}
