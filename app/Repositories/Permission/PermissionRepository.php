<?php

namespace App\Repositories\Permission;

use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Permission;

/**
 * The repository for Permission Model
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    /**
     * @inheritdoc
     */
    protected $model;

    /**
     * @inheritdoc
     */
    public function __construct(Permission $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }
}
