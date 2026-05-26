<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Http\Resources\V2\ArticleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\ArticleRepositoryInterface;
use App\Services\ArticleService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ArticleController extends Controller
{
    use AuthorizesRequests;
    protected $articleRepository;
    protected $articleService;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        ArticleService $articleService
    ) {
        $this->articleRepository = $articleRepository;
        $this->articleService = $articleService;
    }

    public function index(): AnonymousResourceCollection
    {
        $articles = $this->articleRepository->getPublishedArticlesV2();

        return ArticleResource::collection($articles);
    }

    public function show(Article $article): JsonResource
    {
        $this->authorize('view', $article);

        $detailedArticle = $this->articleRepository->findWithDetails($article->id);

        return new ArticleResource($detailedArticle);
    }
}
