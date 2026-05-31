<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class CleanupOldAuditLogs extends Command
{
    protected $signature = 'audit:cleanup {--days=90 : Delete audit logs older than X days}';
    protected $description = 'Clean up old audit logs to maintain database performance';

    public function handle(): int
    {
        $days = $this->option('days');
        $date = now()->subDays($days);

        $deleted = AuditLog::where('timestamp', '<', $date)->delete();

        $this->info("Deleted {$deleted} audit logs older than {$days} days.");
        return Command::SUCCESS;
    }
}
