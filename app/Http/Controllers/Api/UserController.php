<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;

/**
 * @tags Users Management
 */
class UserController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {
        $this->middleware('permission:'.Acl::PERMISSION_USER_LIST)->only('index');
        $this->middleware('permission:'.Acl::PERMISSION_USER_ADD)->only(['store']);
        $this->middleware('permission:'.Acl::PERMISSION_USER_EDIT)->only(['update']);
        $this->middleware('permission:'.Acl::PERMISSION_USER_DELETE)->only('destroy');
    }

    /**
     * Get user list
     * 
     * Display a listing of the resource.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\UserResource,
     *   pagination: array{
     *     current_page: int,
     *     last_page: int,
     *     per_page: int,
     *     total: int
     *  }
     * }
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->serverPaginationFiltering($request->all());

        if(!$users) {
            return $this->errorResponse([], 
                'No users found.', 404
            );
        }
        return $this->okResponse(
            UserResource::collection($users), 
            'User list retrieved successfully.', 
        );
    }

    /**
     * Create User
     * 
     * Store a newly created resource in storage.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\UserResource,
     * }
     * 
     * 
     * @param App\Http\Requests\User\StoreUserRequest  $request
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->create($request->validated());
        
        return $user
            ? $this->okResponse(new UserResource($user), 'User created successfully.')
            : $this->errorResponse([], 'Failed to create user.', 422);
    }

    /**
     * Show User Detail
     * 
     * Display the specified resource.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\UserResource,
     * }
     */
    public function show(User $user)
    {
        return $this->okResponse(new UserResource($user), 'User details retrieved successfully.');
    }

    /**
     * Edit User
     * 
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userRepository->update($user, $request->validated());
        
        return $user
            ? $this->okResponse(new UserResource($user), 'User updated successfully.')
            : $this->errorResponse([], 'Failed to update user.', 422);
    }

    /**
     * Delete User
     * 
     * Remove the specified resource from storage.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: array{},
     * }
     * 
     * @param  \App\Models\User  $user
     */
    public function destroy(User $user)
    {
        $deleted = $this->userRepository->destroy($user);

        return $deleted
            ? $this->okResponse([], 'User deleted successfully.')
            : $this->errorResponse([], 'Failed to delete user.', 422);
    }
}
