<?php

namespace App\Repositories\Role;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

/**
 * The repository for Role Model
 */
class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }
}