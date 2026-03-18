<?php

namespace App\Repositories\Submission;

use App\Models\Submission;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class SubmissionRepository extends BaseRepository implements SubmissionRepositoryInterface
{
    const ITEM_PER_PAGE = 5;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(Submission $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }
    
    /**
     * Chỉ cần method đặc biệt cho Submission
     */
    public function serverPaginationFiltering(array $searchParams): LengthAwarePaginator
    {  
        $limit = Arr::get($searchParams, 'limit', self::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');
        $closure_date = Arr::get($searchParams, 'closure_date', null);
        $final_closure_date = Arr::get($searchParams, 'final_closure_date', null);
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        if (!is_null($closure_date)) {
            $query->where('closure_date', $closure_date);
        }

        if (!is_null($final_closure_date)) {
            $query->where('final_closure_date', $final_closure_date);
        }

        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        return $query->paginate($limit);
    }
    
    /**
     * Method đặc biệt khác cho Submission
     */
    public function getActiveSubmissions()
    {
        return $this->model->where('final_closure_date', '>', now())->get();
    }

    /**
     * Get submission with status.
     */
    public function getSubmissionWithStatus($submissionId)
    {
        return $this->model->findOrFail($submissionId);
    }
}