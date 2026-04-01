<?php

namespace App\Http\Controllers\Api;

use App\Acl\Acl;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\Api\RoleResource;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @tags Roles Management
 */
class RoleController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
        protected PermissionRepositoryInterface $permissionRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_ROLE_MANAGE)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_ROLE_EDIT)->only('update');
    }
    /**
     * Get role list
     * 
     * Display a listing of the resource.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\RoleResource,
     * }
     */
    public function index(Request $request)
    {
        $roles = $this->roleRepository->all();
        $roles->load('permissions');

        return $this->okResponse(
            RoleResource::collection($roles), 
            'Role list retrieved successfully.', 
        );
    }

    /**
     * Update role.
     * 
     * Update the specified resource in storage.
     * 
     * @authenticated
     * 
     * @response array{
     *  message: string,
     *  data: \App\Http\Resources\Api\RoleResource,
     * }
     * 
     * @param \App\Http\Requests\Role\UpdateRoleRequest $request
     * @param \Spatie\Permission\Models\Role $role
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role = $this->roleRepository->update($role, $request->validated());

        return $this->okResponse(
            new RoleResource($role),
            'Role updated successfully.',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
