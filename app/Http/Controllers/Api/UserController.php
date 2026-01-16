<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Http\Resources\Api\UserResource;
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
        // $this->middleware('permission:' . Acl::PERMISSION_USER_LIST)->only('index');
        // $this->middleware('permission:' . Acl::PERMISSION_USER_ADD)->only(['create', 'store']);
        // $this->middleware('permission:' . Acl::PERMISSION_USER_EDIT)->only(['edit', 'update']);
        // $this->middleware('permission:' . Acl::PERMISSION_USER_DELETE)->only('destroy');
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
     * Get Create User Form
     * 
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
