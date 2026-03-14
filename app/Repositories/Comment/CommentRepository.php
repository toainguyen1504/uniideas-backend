<?php

namespace App\Repositories\Comment;

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

            // Lấy submission từ idea
            $idea = \App\Models\Idea::findOrFail($data['idea_id']);
            $submission = $idea->submission;

            // Kiểm tra trạng thái comment
            if (!$submission->status->canComment()) {
                return response()->json([
                    'message' => 'Comments are closed after Final Closure Date.'
                ], 422);
            }

            $comment = $this->model->create($data);

            DB::commit();
            return $comment;
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
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

            // Lấy submission từ idea
            $submission = $model->idea->submission;

            // Kiểm tra trạng thái READ-ONLY
            if (!$submission->status->canBeModified()) {
                return response()->json([
                    'message' => 'Submission is read-only. Cannot update comment.'
                ], 422);
            }


            $model->update($data);

            DB::commit();
            return $model;
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
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
