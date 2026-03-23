<?php

namespace App\Repositories\React;

use App\Enum\ReactEnum;
use App\Models\React;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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

            DB::commit();

            return $react;
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

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
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
     * Lấy tổng số phản ứng theo loại (LIKE, DISLIKE).
     *
     */
    public function countReacts(int $reactType): int
    {
        return React::where('react', $reactType)->count();
    }
}
