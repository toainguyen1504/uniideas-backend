<?php

namespace App\Repositories\React;

use App\Enum\ReactEnum;
use App\Models\React;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

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
}