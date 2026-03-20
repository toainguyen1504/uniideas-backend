<?php

namespace App\Services;

use App\Enum\ReactEnum;
use App\Repositories\Statistics\StatisticsRepository;
use App\Repositories\Statistics\StatisticsRepositoryInterface;

class StatisticsService
{
    public function __construct(
        protected StatisticsRepositoryInterface $statisticsRepository
    ) {}

    public function getOverview(): array
    {
        return [
            'total_ideas'    => $this->statisticsRepository->countIdeas(),
            'total_likes'    => $this->statisticsRepository->countReacts(ReactEnum::LIKE->value),
            'total_dislikes' => $this->statisticsRepository->countReacts(ReactEnum::DISLIKE->value),
            'total_comments' => $this->statisticsRepository->countComments(),
            'by_department'  => $this->statisticsRepository->getDepartmentStats(),
        ];
    }
}


