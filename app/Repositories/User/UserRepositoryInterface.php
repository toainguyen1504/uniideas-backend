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

    /**
     * Get users by QA Coordinator role, optionally filtered by department id
     */
    public function getUsersByQACoordinatorRole(?int $departmentId = null);

    /**
     * Get notifications of user.
     */
    public function getUserNotifications($model, bool $unreadOnly = false, int $perPage = 20): LengthAwarePaginator;

    /**
     * Get count of unread notifications of user.
     */
    public function getUnreadCount($model): int;

    /**
     * Mark a notification as read for the user.
     */
    public function markAsRead($model, string $notificationId): bool;

    /**
     * Mark all notifications as read for the user.
     */
    public function markAllAsRead($model): void;
}
