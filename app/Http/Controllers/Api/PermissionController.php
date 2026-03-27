<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Http\Resources\Api\PermissionResource;
use App\Repositories\Permission\PermissionRepositoryInterface;

/**
 * @tags Permissions Management
 */
class PermissionController extends Controller
{
    use ApiResponses;
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository,
    ) {
        // $this->middleware('permission:' . Acl::PERMISSION_PERMISSION_LIST)->only('index');   
    }
    /**
     * List all permissions.
     * 
     * Display a listing of the resource.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: array<\App\Http\Resources\Api\PermissionResource>,
     * }
     * 
     * @response 403 {
     *   "message": "Forbidden"
     * }
     */
    public function index()
    {
        $permissions = $this->permissionRepository->all();

        return $this->okResponse(
            PermissionResource::collection($permissions),
            'Permission list retrieved successfully.',
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
