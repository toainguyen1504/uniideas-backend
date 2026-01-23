<?php

namespace App\Repositories\Submission;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SubmissionRepositoryInterface
{
    /**
     * Get paginated submissions with filtering
     */
    public function serverPaginationFiltering(array $searchParams): LengthAwarePaginator;
    
}