<?php

namespace App\Repositories\Ideas;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Idea;

interface IdeaRepositoryInterface extends RepositoryInterface
{
    /**
     * Phân trang + filter theo search params
     */
    public function serverPaginationFiltering(array $searchParams): LengthAwarePaginator;

    /**
     * Lấy tất cả ideas đã được duyệt (approved)
     */
    public function getAllApproved(): Collection;

    /**
     * Lấy ideas theo submission id
     */
    public function getBySubmissionId(int $submissionId): Collection;

    /**
     * Lấy ideas theo category id
     */
    public function getByCategoryId(int $categoryId): Collection;

}
