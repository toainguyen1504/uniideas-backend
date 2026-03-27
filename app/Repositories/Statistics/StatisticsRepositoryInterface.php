<?php

namespace App\Repositories\Statistics;

interface StatisticsRepositoryInterface
{
    /**
     * Get statistics by department:
     * - Number of ideas
     * - Number of likes
     * - Number of dislikes
     * - Number of comments
     */
    public function getDepartmentStatistics();
}
