<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ArchiveOldArticlesCommand extends Command
{
    protected $signature = 'articles:archive {days? : Number of days} {--dry-run}';
    protected $description = 'Archive unpublished articles older than a specified number of days';

    public function handle()
    {
        $days = $this->argument('days') ?? 30;
        $job = new \App\Jobs\ArchiveArticlesJob($days, $this->option('dry-run'));
        dispatch_sync($job);

        $this->info($this->option('dry-run') ? "Dry Run: {$job->archivedCount} would be archived." : "Archived {$job->archivedCount} articles.");
    }
}
