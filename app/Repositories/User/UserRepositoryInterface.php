<?php

namespace App\Repositories\User;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * The repository interface for the User Model
 */
interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator;
}
