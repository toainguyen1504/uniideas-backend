<?php

namespace App\Repositories\Comment;

use App\Jobs\NotifyCommentIdeaJob;
use App\Models\Comment;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * The repository for Comment Model
 */
class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(Comment $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Get comments by Idea.
     */
    public function getCommentsByIdea(int $ideaId): Collection
    {
        return $this->model->where('idea_id', $ideaId)->get();
    }

    /**
     * Count 'comments' for a given idea.
     */
    public function countCommentsByIdea(int $ideaId): int
    {
        return $this->model->where('idea_id', $ideaId)->count();
    }


    /**
     * Lấy tổng số bình luận trong hệ thống.
     */
        public function countComments(): int
    {
        return DB::table('comments')->count();
    }
}
