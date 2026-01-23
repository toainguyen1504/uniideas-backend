<?php

namespace App\Repositories\Category;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function serverPaginationFiltering(array $searchParams): LengthAwarePaginator;
    
    public function getAllActive(): Collection;
    
    public function getByParentId(?int $parentId = null): Collection;
}