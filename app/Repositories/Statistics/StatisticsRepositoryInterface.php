<?php

namespace App\Repositories\Statistics;

interface StatisticsRepositoryInterface
{
    /**
     * Lấy dữ liệu thống kê tổng quan của hệ thống
     *
     * @return array
     */
    public function getOverview(): array;
}
