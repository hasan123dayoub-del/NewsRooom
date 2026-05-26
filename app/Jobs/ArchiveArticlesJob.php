<?php

namespace App\Jobs;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ArchiveArticlesJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $days;
    public $isDryRun;
    public $archivedCount = 0;

    public function __construct($days, $isDryRun = false)
    {
        $this->days = $days;
        $this->isDryRun = $isDryRun;
    }

    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays((int)$this->days);
        $query = Article::where('status', '!=', 'published')
            ->where('created_at', '<=', $cutoffDate)
            ->where('updated_at', '<=', $cutoffDate);

        $this->archivedCount = $query->count();

        if (!$this->isDryRun && $this->archivedCount > 0) {
            $query->update(['status' => 'draft']);
        }
    }
}
