<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use App\Acl\Acl;

/**
 * The repository for User Model
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    const ITEM_PER_PAGE = 5;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(User $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::ITEM_PER_PAGE);

        $query = $this->userFilter($searchParams);

        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        return $query->paginate($limit);
    }

    /**
     * @inheritdoc
     */
    private function userFilter(array $searchParams)
    {
        $keyword = Arr::get($searchParams, 'search', '');
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query()->with(['roles', 'department']);

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', '%' . $keyword . '%')
                    ->orWhere('name', 'like', '%' . $keyword . '%');
            });
        }

        if (! is_null($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Get users by QA Coordinator role
     */
    public function getUsersByQACoordinatorRole(?int $departmentId = null)
    {
        $query = $this->model->whereHas('roles', function ($q) {
            $q->where('name', Acl::ROLE_QA_COORDINATOR);
        });

        if (!is_null($departmentId)) {
            $query->where('department_id', $departmentId);
        }

        return $query->get();
    }

    /**
     * Get notifications of user.
     */
    public function getUserNotifications($model, bool $unreadOnly = false, int $perPage = 20): LengthAwarePaginator
    {
        $query = $model->notifications();

        if ($unreadOnly) {
            $query = $model->unreadNotifications();
        }

        return $query->paginate($perPage);
    }

    /**
     * Get count of unread notifications of user.
     */
    public function getUnreadCount($model): int
    {
        return $model->unreadNotifications()->count();
    }

    /**
     * Mark a notification as read for the user.
     */
    public function markAsRead($model, string $notificationId): bool
    {
        $notification = $model->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for the user.
     */
    public function markAllAsRead($model): void
    {
        $model->unreadNotifications->markAsRead();
    }
}
