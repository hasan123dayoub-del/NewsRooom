<?php

namespace App\Repositories\Eloquent;

use App\Models\Article;
use App\Repositories\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function getPublishedArticlesV1(): LengthAwarePaginator
    {
        return Article::where('status', 'published')
            ->with(['user.profile'])
            ->latest()
            ->paginate(10);
    }

    public function getPublishedArticlesV2(): LengthAwarePaginator
    {
        return Article::where('status', 'published')
            ->with(['user.profile', 'tags'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);
    }

    public function findWithDetails(int $id): Article
    {
        return Article::with([
            'user.profile',
            'tags',
            'attachments',
            'comments.user.profile'
        ])->findOrFail($id);
    }

    public function create(array $data): Article
    {
        return Article::create($data);
    }

    public function update(int $id, array $data): Article
    {
        $article = Article::findOrFail($id);
        $article->update($data);
        return $article;
    }
}
