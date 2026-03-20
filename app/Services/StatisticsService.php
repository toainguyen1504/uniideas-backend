<?php

namespace App\Services;

use App\Repositories\Statistics\StatisticsRepository;

class StatisticsService
{
    public function __construct(
        protected StatisticsRepository $statisticsRepository
    ) {}

    public function getOverview(): array
    {
        return $this->statisticsRepository->getOverview();
    }
}

