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

    /**
     * Get idea by id
     */
    public function getIdeaById($ideaId);

    /**
     * Get top 3 ideas have is_featured = true in a submission and have most court likes.
     */
    public function getTopFeaturedIdeas($submissionId, int $limit = 3);

    /**
     * Count total ideas in the system.
     */
    public function countIdeas(): int;
}
