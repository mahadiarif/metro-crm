<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Domains\Analytics\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    public function dashboard(): JsonResponse
    {
        return response()->json([
            'quick_stats' => $this->analyticsService->getQuickStats(),
            'funnel' => $this->analyticsService->getSalesFunnel(),
            'conversion' => $this->analyticsService->getConversionStats(),
            'proposals' => $this->analyticsService->getProposalStats(),
            'performance' => $this->analyticsService->getExecutivePerformance(),
            'revenue_trend' => $this->analyticsService->getMonthlyRevenueTrend(),
            'forecast' => $this->analyticsService->getRevenueForecast(),
        ]);
    }
}
