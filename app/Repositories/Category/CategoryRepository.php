<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;
use App\Enum\CategoryStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    const ITEM_PER_PAGE = 15;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Lấy danh sách phân trang với filter
     */
    public function serverPaginationFiltering(array $searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query();

        // Filter theo keyword
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        // Filter theo status
        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        return $query->paginate($limit);
    }

    /**
     * Lấy tất cả categories active
     */
    public function getAllActive(): Collection
    {
        return $this->model->where('status', CategoryStatus::ACTIVE)
            ->orderBy('name')
            ->get();
    }

    /**
     * Lấy categories theo parent
     */
    public function getByParentId(?int $parentId = null): Collection
    {
        return $this->model->where('parent_id', $parentId)
            ->where('status', CategoryStatus::ACTIVE)
            ->orderBy('name')
            ->get();
    }
}