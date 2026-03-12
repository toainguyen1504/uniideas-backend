<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\NotificationResource;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification as Notification;
use App\Http\Controllers\Controller;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class NotificationController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository,
        protected UserRepositoryInterface $userRepository,
    ) {
        //
    }

    /**
     * Get notifications of user.
     *
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @response array{
     *   message: string,
     *   data: array{
     *     notifications: array<\App\Http\Resources\Api\NotificationResource>,
     *     unread_count: int,
     *     pagination: array{
     *       current_page: int,
     *       last_page: int,
     *       per_page: int,
     *       total: int
     *     }
     *   }
     * }
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $unreadOnly = $request->boolean('unread_only');
        
        $notifications = $this->userRepository->getUserNotifications($user, $unreadOnly);
        $unreadCount = $this->userRepository->getUnreadCount($user);

        return $this->okResponse([
            'notifications' => NotificationResource::collection($notifications),
            'unread_count' => $unreadCount,
            'pagination'   => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ]
        ], 'Notifications retrieved successfully.');
    }

    /**
     * Mark a notification as read for the authenticated user.
     *
     * @param string $id Notification ID belonging to the current user
     * @return \Illuminate\Http\JsonResponse
     *
     * @response array{
     *   message: string,
     *   data: array{
     *     notifications: array<\App\Http\Resources\Api\NotificationResource>,
     *   }
     * }
     *
     * @response 404 {
     *   "message": "Cannot find out the notification.",
     *   "data": []
     * }
     */
    public function markAsRead(string $id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead(); 
            return $this->okResponse([
                'notification' => new NotificationResource($notification)
            ], 'Notification marked as read successfully.');
        }

        return $this->errorResponse([], 'Cannot find out the notification.', 404);
    }

    /**
     * Mark all unread notifications as read for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @response array{
     *   message: string,
     *   data: array{
     *     notifications: array<\App\Http\Resources\Api\NotificationResource>,
     *      pagination: array{
     *          current_page: int,
     *          last_page: int,
     *          per_page: int,
     *          total: int
     *      }
     *   }
     * }
     */
    public function markAllAsRead()
    {
        $user = auth()->user();

        $this->userRepository->markAllAsRead($user);

        $notifications = $this->userRepository->getUserNotifications($user, false);
        $unreadCount = $this->userRepository->getUnreadCount($user);

        return $this->okResponse([
            'notifications' => NotificationResource::collection($notifications),
            'unread_count' => $unreadCount,
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ]
        ], 'All notifications marked as read successfully.');
    }
}
