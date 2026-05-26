<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): JsonResponse
    {
        $stats = $this->dashboardService->getDashboardStats();

        $topTags = $this->dashboardService->getTopTags();

        return response()->json([
            'status' => 'success',
            'data' => [
                'stats' => $stats,
                'top_tags' => $topTags
            ]
        ]);
    }
}
