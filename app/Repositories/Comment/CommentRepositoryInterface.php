<?php

namespace App\Repositories\Comment;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * The repository interface for the Comment Model
 */
interface CommentRepositoryInterface extends RepositoryInterface
{
    /**
     * Get comments by Idea.
     */
    public function getCommentsByIdea(int $ideaId): Collection;

    /**
     * Count 'comments' for a given idea.
     */
    public function countCommentsByIdea(int $ideaId): int;
}
