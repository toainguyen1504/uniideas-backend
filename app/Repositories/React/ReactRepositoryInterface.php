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

    /**
     * Get count all Likes of the system.
     */
    public function countLikesReact(): int;

    /**
    * Get count all Dislikes of the system.
    */
    public function countDislikesReact(): int;

    /**
    * Find a user's react for a specific idea, if it exists.
    */
    public function findUserReact($userId, $ideaId);

    /**
    * Handle deletion of a react, adjusting idea's total_likes if necessary.
    */
    public function delete($reactModel);

    /**
    * Create or update a react for a user and idea. If the react already exists, it will be updated with the new value; otherwise, a new react will be created.
    */
    public function updateOrCreate($userId, $ideaId, $value, $isAnonymous);
}
