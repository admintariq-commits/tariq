<?php

namespace App\Console\Commands;

use App\Models\Graduate;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckUnemploymentAlerts extends Command
{
    protected $signature = 'unemployment:check-alerts';
    protected $description = 'Check graduates who are long-term unemployed and send alerts';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle(): int
    {
        $graduates = Graduate::where('employment_status', 'unemployed')
            ->where('graduation_date', '<', now()->subMonths(6))
            ->get();

        $count = 0;
        foreach ($graduates as $graduate) {
            $notification = $this->notificationService->sendUnemploymentAlert($graduate);
            if ($notification) {
                $count++;
            }
        }

        $this->info("Sent {$count} unemployment alerts.");
        return Command::SUCCESS;
    }
}
