<?php

namespace App\Repositories\Department;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository interface for the Department Model
 */
interface DepartmentRepositoryInterface extends RepositoryInterface
{
    /**
     * Get list users by department id
     */
    public function getUsersByDepartmentId(int $departmentId);
}
