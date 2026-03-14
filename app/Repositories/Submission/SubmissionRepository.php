<?php

namespace App\Repositories\Submission;

use App\Models\Submission;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubmissionRepository extends BaseRepository implements SubmissionRepositoryInterface
{
    public function __construct(Submission $model)
    {
        parent::__construct($model);
    }
    
    
    /**
     * Chỉ cần method đặc biệt cho Submission
     */
    public function serverPaginationFiltering(array $params = []): LengthAwarePaginator
    {
        $conditions = [];
        
        if (!empty($params['search'])) {
            $conditions['where_like'] = [
                'name' => "%{$params['search']}%"
            ];
        }
        
        $data = [
            'with_counts' => [
                ['relation' => 'ideas']
            ],
            'order_by' => [
                'column' => 'created_at',
                'type' => 'desc'
            ],
            'pagination' => $params['per_page'] ?? 5,
        ];
        
        if (!empty($conditions)) {
            $data['conditions'] = $conditions;
        }
        
        return $this->advancedGet($data);
    }
    
    /**
     * Method đặc biệt khác cho Submission
     */
    public function getActiveSubmissions()
    {
        return $this->model->where('final_closure_date', '>', now())->get();
    }
}