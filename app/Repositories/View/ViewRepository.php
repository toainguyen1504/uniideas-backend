<?php

namespace App\Repositories\View;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use App\Models\View;

/**
 * The repository for View Model
 */
class ViewRepository extends BaseRepository implements ViewRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(View $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }
}