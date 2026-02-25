<?php

namespace App\Repositories\Submission;

use App\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SubmissionRepositoryInterface extends RepositoryInterface
{
    /**
     * Get paginated submissions with filtering
     */
    public function serverPaginationFiltering(array $searchParams): LengthAwarePaginator;
    
}