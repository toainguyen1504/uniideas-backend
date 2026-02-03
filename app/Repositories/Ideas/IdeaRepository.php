<?php

namespace App\Repositories\Idea;

use App\Models\Idea;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class IdeaRepository extends BaseRepository implements IdeaRepositoryInterface
{
    const ITEM_PER_PAGE = 5;

    protected $model;

    public function __construct(Idea $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    public function serverPaginationFiltering(array $searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::ITEM_PER_PAGE);

        $query = $this->ideaFilter($searchParams);

        $query->orderBy(
            Arr::get($searchParams, 'sort_by', 'created_at'),
            Arr::get($searchParams, 'sort_order', 'desc')
        );

        return $query->paginate($limit);
    }

    private function ideaFilter(array $searchParams)
    {
        $keyword      = Arr::get($searchParams, 'search', '');
        $status       = Arr::get($searchParams, 'status', null);
        $categoryId   = Arr::get($searchParams, 'category_id', null);
        $submissionId = Arr::get($searchParams, 'submission_id', null);

        $query = $this->model->query()->with(['user', 'category', 'submission']);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('content', 'like', '%' . $keyword . '%');
            });
        }

        if (! is_null($status)) {
            $query->where('status', $status);
        }

        if (! is_null($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (! is_null($submissionId)) {
            $query->where('submission_id', $submissionId);
        }

        return $query;
    }

    public function getAllApproved(): Collection
    {
        return $this->model->where('status', 'approved')->get();
    }

    public function getBySubmissionId(int $submissionId): Collection
    {
        return $this->model->where('submission_id', $submissionId)->get();
    }

    public function getByCategoryId(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    public function create(array $data, $file = null): Idea
    {
        if ($file) {
            $data['file_path'] = $file->store('uploads');
        }
        return parent::create($data);
    }

    public function update(int $id, array $data, $file = null): Idea
    {
        $idea = $this->find($id);

        if ($file) {
            $data['file_path'] = $file->store('uploads');
        }

        parent::update($idea, $data);

        return $idea;
    }

    public function delete(int $id): bool
    {
        $idea = $this->find($id);
        return parent::destroy($idea);
    }
}
