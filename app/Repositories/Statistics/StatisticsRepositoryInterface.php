<?php

namespace App\Repositories\Statistics;

interface StatisticsRepositoryInterface
{
    /**
     * Lấy thống kê chi tiết theo từng phòng ban:
     * - Số lượng ý tưởng
     * - Số lượng likes
     * - Số lượng dislikes
     * - Số lượng bình luận
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDepartmentStats();
}
