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
     * Override create method.
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $data['idea_id'] = Arr::get($data, 'idea_id');

            $comment = $this->model->create($data);

            NotifyCommentIdeaJob::dispatch($comment, $comment->idea);

            DB::commit();

            return $comment;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }
    /**
     * Override update method.
     */
    public function update($model, $data)
    {
        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $data['idea_id'] = $model->idea_id;

            $model->update($data);

            NotifyCommentIdeaJob::dispatch($model, $model->idea);

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
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
}