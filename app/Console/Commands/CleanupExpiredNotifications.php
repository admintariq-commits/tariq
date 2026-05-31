<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;

class CleanupExpiredNotifications extends Command
{
    protected $signature = 'notifications:cleanup {--days=30 : Delete expired notifications older than X days}';
    protected $description = 'Clean up expired notifications';

    public function handle(): int
    {
        $days = $this->option('days');

        // Delete expired notifications
        $deleted = Notification::where('expires_at', '<', now())
            ->orWhere('created_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Deleted {$deleted} expired notifications.");
        return Command::SUCCESS;
    }
}
