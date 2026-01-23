<?php

namespace App\Repositories\Department;

use App\Models\Department;
use App\Repositories\BaseRepository;

/**
 * The repository for Department Model
 */
class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(Department $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Get list users by department id
     */
    public function getUsersByDepartmentId(int $departmentId)
    {
        return $this->model->find($departmentId)?->users;
    }
}