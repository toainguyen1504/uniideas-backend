<?php

namespace App\Http\Controllers\Api;

use App\Acl\Acl;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\RoleResource;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

/**
 * @tags Roles Management
 */
class RoleController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
    ) {
        $this->middleware('permission:'.Acl::PERMISSION_ROLE_MANAGE)->only('index');
    }
    /**
     * Get role list
     * 
     * Display a listing of the resource.
     * 
     * @authenticated
     * 
     * $response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\RoleResource,
     * }
     */
    public function index(Request $request)
    {
        $roles = $this->roleRepository->all();

        return $this->okResponse(
            RoleResource::collection($roles), 
            'Role list retrieved successfully.', 
        );
    }

    /**
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
