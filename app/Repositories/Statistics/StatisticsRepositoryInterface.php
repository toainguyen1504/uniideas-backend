<?php

namespace App\Repositories\Statistics;

interface StatisticsRepositoryInterface
{
    /**
     * Lấy tổng số ý tưởng trong hệ thống.
     *
     * @return int
     */
    public function countIdeas(): int;

    /**
     * Lấy tổng số bình luận trong hệ thống.
     *
     * @return int
     */
    public function countComments(): int;

    /**
     * Lấy tổng số phản ứng theo loại (LIKE, DISLIKE).
     *
     * @param int $reactType Giá trị enum ReactEnum (VD: ReactEnum::LIKE->value)
     * @return int
     */
    public function countReacts(int $reactType): int;

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
