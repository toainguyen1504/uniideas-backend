<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StatisticsResource;
use App\Services\StatisticsService;
use App\Traits\ApiResponses;

class StatisticsController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected StatisticsService $statisticsService
    ) 
    {}
    /**
     * Show Statistics Overview
     *
     * @authenticated
     *
     * @response array{
     *      message: string,
     *      data: \App\Http\Resources\Api\StatisticsResource
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        $stats = $this->statisticsService->getOverview();

        return $this->okResponse([
            new StatisticsResource($stats),
        ], 'Statistics list retrieved successfully.');
    }
}
