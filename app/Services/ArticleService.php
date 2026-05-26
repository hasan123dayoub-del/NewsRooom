<?php

namespace App\Services;

use App\Repositories\ArticleRepositoryInterface;
use App\Models\User;
use App\Models\Article;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function createArticle(User $user, array $data): Article
    {
        $article = Article::create([
            'user_id' => $user->id,
            'title'   => $data['title'],
            'content' => $data['content'],
            'status'  => $data['status'],
        ]);

        if (isset($data['tags'])) {
            $article->tags()->sync($data['tags']);
        }

        return $article;
    }
    public function updateArticle(Article $article, array $data): Article
    {
        $updatedArticle = $this->articleRepository->update($article->id, $data);

        if (isset($data['tags'])) {
            $updatedArticle->tags()->sync($data['tags']);
        }

        return $updatedArticle;
    }
}
