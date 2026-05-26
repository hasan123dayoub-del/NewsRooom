<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface
{
    public function getPublishedArticlesV1(): LengthAwarePaginator;

    public function getPublishedArticlesV2(): LengthAwarePaginator;

    public function findWithDetails(int $id): Article;

    public function create(array $data): Article;

    public function update(int $id, array $data): Article;
}
