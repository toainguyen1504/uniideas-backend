<?php

namespace App\Repositories\React;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * The repository interface for the React Model
 */
interface ReactRepositoryInterface extends RepositoryInterface
{
    /**
     * Get reacts by Idea.
     */
    public function getReactsByIdea(int $ideaId): Collection;

    /**
     * Count 'like' reacts for an idea.
     */
    public function countLikesByIdea(int $ideaId): int;

     /**
     * Count 'dislike' reacts for an idea.
     */
    public function countDislikesByIdea(int $ideaId): int;
}
