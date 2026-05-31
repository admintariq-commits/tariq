<?php

namespace App\Console\Commands;

use App\Services\AnalyticsService;
use Illuminate\Console\Command;

class GenerateAnalyticsReports extends Command
{
    protected $signature = 'analytics:generate {--type=all : Report type: all, trends, salary, skills}';
    protected $description = 'Generate analytics reports';

    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        parent::__construct();
        $this->analyticsService = $analyticsService;
    }

    public function handle(): int
    {
        $type = $this->option('type');

        if ($type === 'all' || $type === 'trends') {
            $this->analyticsService->generateEmploymentTrendsReport();
            $this->info('Generated employment trends report');
        }

        if ($type === 'all' || $type === 'salary') {
            $this->analyticsService->generateSalaryAnalysisReport();
            $this->info('Generated salary analysis report');
        }

        if ($type === 'all' || $type === 'skills') {
            $this->analyticsService->generateSkillsGapReport();
            $this->info('Generated skills gap report');
        }

        return Command::SUCCESS;
    }
}
