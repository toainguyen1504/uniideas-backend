<?php

namespace App\Repositories\React;

use App\Enum\AnonymousEnum;
use App\Enum\ReactEnum;
use App\Models\React;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * The repository for React Model
 */
class ReactRepository extends BaseRepository implements ReactRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(React $model)
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

            $react = $this->model->create($data);

            $delta = $this->likeDelta(null, $react->react);
            if ($delta !== 0) {
                $react->idea()
                    ->where('id', $react->idea_id)
                    ->increment('total_likes', $delta);
            }

            DB::commit();

            return $react;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating react: ' . $e->getMessage());
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

            $previousReact = $model->react;
            
            $model->update($data);
            $newReact = $model->react;
            $delta = $this->likeDelta($previousReact, $newReact);

            if ($delta !== 0) {
                $model->idea()
                    ->where('id', $model->idea_id)
                    ->where('total_likes', '>=', 0)
                    ->increment('total_likes', $delta);
            }

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating react: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Calculate the change in likes based on old and new react values.
     */
    private function likeDelta($old, $new): int
    {
        return match (true) {
            $old !== ReactEnum::LIKE && $new === ReactEnum::LIKE => 1,
            $old === ReactEnum::LIKE && $new !== ReactEnum::LIKE => -1,
            default => 0,
        };
    }

    /**
     * Get reacts by Idea.
     */
    public function getReactsByIdea(int $ideaId): Collection
    {
        return $this->model->where('idea_id', $ideaId)->get();
    }

    /**
     * Count 'like' reacts for an idea.
     */
    public function countLikesByIdea(int $ideaId): int
    {
        $query = $this->model->where('idea_id', $ideaId)
            ->where('react', ReactEnum::LIKE)->count();

        return $query;
    }

    /**
     * Count 'dislike' reacts for an idea.
     */
    public function countDislikesByIdea(int $ideaId): int
    {
        $query = $this->model->where('idea_id', $ideaId)
            ->where('react', ReactEnum::DISLIKE)->count();

        return $query;
    }
    
    /**
     * Get count all Likes of the system.
     */
    public function countLikesReact(): int
    {
        return $this->model->where('react', ReactEnum::LIKE)->count();
    }

    /**
    * Get count all Dislikes of the system.
    */
    public function countDislikesReact(): int
    {
        return $this->model->where('react', ReactEnum::DISLIKE)->count();
    }

   /**
    * Find a user's react for a specific idea, if it exists.
    */
    public function findUserReact($userId, $ideaId)
    {
        return $this->model->where('user_id', $userId)
            ->where('idea_id', $ideaId)
            ->first();
    }

   /**
    * Handle deletion of a react, adjusting idea's total_likes if necessary.
    */
    public function delete($reactModel)
    {
        return $reactModel->delete();
    }

   /**
    * Create or update a react for a user and idea. If the react already exists, it will be updated with the new value; otherwise, a new react will be created.
    */
    public function updateOrCreate($userId, $ideaId, $value, $isAnonymous)
    {
        return $this->model->updateOrCreate(
            ['user_id' => $userId, 'idea_id' => $ideaId],
            [
                'react'        => $value,
                'is_anonymous' => $isAnonymous ?? AnonymousEnum::NOT_ANONYMOUS->value
            ]
        );
    }
}
