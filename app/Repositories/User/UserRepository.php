<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * The repository for User Model
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    const ITEM_PER_PAGE = 20;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(User $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::ITEM_PER_PAGE);

        $query = $this->userFilter($searchParams);

        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        return $query->paginate($limit);
    }

    /**
     * @inheritdoc
     */
    private function userFilter(array $searchParams)
    {
        $keyword = Arr::get($searchParams, 'search', '');
        $role = Arr::get($searchParams, 'role', '');
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query()->with('roles');

        if ($role) {
            $query->role($role);
        }

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
            $query->whereAny([
                'name',
                'email',
                'phone_number',
                'id',
            ], 'LIKE', '%' . $keyword . '%');
        }

        if (! is_null($status)) {
            $query->where('status', $status);
        }

        return $query;
    }
}
