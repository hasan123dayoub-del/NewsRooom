<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class SendNotificationToChunkJob implements ShouldQueue
{
    use Queueable;
    public int $tries = 3;
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $userIds,
        protected Article $article
    ) {
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subscribers = User::whereIn('id', $this->userIds)->get();
        if ($subscribers->isEmpty()) {
            return;
        }
        Notification::send($subscribers, new NewArticleNotification($this->article));
    }
}
