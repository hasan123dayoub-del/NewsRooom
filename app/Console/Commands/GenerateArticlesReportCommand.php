<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateReportJob;

class GenerateArticlesReportCommand extends Command
{
    protected $signature = 'articles:report {--dry-run : Simulate the report generation without saving to log file}';
    protected $description = 'Generate a monthly report of published articles per writer';

    public function handle()
    {
        $job = new GenerateReportJob($this->option('dry-run'));
        dispatch_sync($job);

        $this->table(['ID', 'Name', 'Articles'], $job->reportData);
        if ($this->option('dry-run')) {
            $this->info('Dry Run Mode: Report generated but NOT saved to log file.');
        } else {
            $this->info('Report successfully saved to log file.');
        }
    }
}
