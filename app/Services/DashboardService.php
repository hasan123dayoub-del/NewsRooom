<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;

class DashboardService
{
    public function getDashboardStats(): array
    {
        $cachedData = Cache::tags(['dashboard_stats'])->get('dashboard_data');

        if ($cachedData !== null) {
            return $cachedData;
        }

        $lock = Cache::lock('calculating_dashboard_stats', 10);

        try {
            $lock->block(5);

            $cachedData = Cache::tags(['dashboard_stats'])->get('dashboard_data');
            if ($cachedData !== null) {
                return $cachedData;
            }

            $stats = [
                'articles_count' => Article::count(),
                'comments_count' => Comment::count(),
                'top_writers'    => User::where('role', 'writer')
                    ->withCount('articles')
                    ->orderBy('articles_count', 'desc')
                    ->take(5)
                    ->get()
                    ->toArray(),
            ];

            Cache::tags(['dashboard_stats'])->put('dashboard_data', $stats, now()->addHours(2));

            return $stats;
        } catch (LockTimeoutException $e) {
            return [];
        } finally {
            $lock->release();
        }
    }

    public function getTopTags(): array
    {
        return Cache::tags(['tags_stats'])->remember('top_tags_data', now()->addHours(4), function () {
            return Tag::withCount('articles')
                ->orderBy('articles_count', 'desc')
                ->take(10)
                ->get()
                ->toArray();
        });
    }

    public function clearDashboardCache(): void
    {
        Cache::tags(['dashboard_stats'])->flush();
    }
}
