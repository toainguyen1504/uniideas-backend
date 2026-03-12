<?php

namespace App\Repositories\Notification;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification as Notification;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

/**
 * The repository for Notification Model
 */
class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    const ITEM_PER_PAGE = 5;
    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(Notification $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }
}
