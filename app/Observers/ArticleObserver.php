<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\DashboardService;
use App\Events\ArticlePublished;

class ArticleObserver
{
    protected DashboardService $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        if ($article->status === 'published') {
            $this->dashboardService->clearDashboardCache();
            ArticlePublished::dispatch($article);
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        if ($article->isDirty('status') && $article->status === 'published') {
            $this->dashboardService->clearDashboardCache();
            ArticlePublished::dispatch($article);
        }
        if ($article->isDirty('status')) {
            $this->dashboardService->clearDashboardCache();
            return;
        }

        if ($article->status === 'published' && ($article->isDirty('title') || $article->isDirty('content'))) {
            $this->dashboardService->clearDashboardCache();
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        if ($article->status === 'published') {
            $this->dashboardService->clearDashboardCache();
        }
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        if ($article->status === 'published') {
            $this->dashboardService->clearDashboardCache();
        }
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        if ($article->status === 'published') {
            $this->dashboardService->clearDashboardCache();
        }
    }
}
