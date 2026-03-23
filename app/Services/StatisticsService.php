<?php

namespace App\Services;

use App\Enum\ReactEnum;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Ideas\IdeaRepositoryInterface;
use App\Repositories\React\ReactRepositoryInterface;
use App\Repositories\Statistics\StatisticsRepository;
use App\Repositories\Statistics\StatisticsRepositoryInterface;

class StatisticsService
{
    public function __construct(
        protected StatisticsRepositoryInterface $statisticsRepository,
        protected IdeaRepositoryInterface $IdeaRepository,
        protected CommentRepositoryInterface $CommentRepository,
        protected ReactRepositoryInterface $ReactRepository
    ) {
        //
    }

    public function getOverview(): array
    {
        return [
            'total_ideas'    => $this->IdeaRepository->countIdeas(),
            'total_likes'    => $this->ReactRepository->countReacts(ReactEnum::LIKE->value),
            'total_dislikes' => $this->ReactRepository->countReacts(ReactEnum::DISLIKE->value),
            'total_comments' => $this->CommentRepository->countComments(),
            'by_department'  => $this->statisticsRepository->getDepartmentStats(),
        ];
    }
}
