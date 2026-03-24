<?php

namespace App\Http\Controllers\Api;

use App\Acl\Acl;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\Api\DepartmentResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Department;
use App\Repositories\Department\DepartmentRepository;

/**
 * @tags Departments Management
 */
class DepartmentController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected DepartmentRepository $departmentRepository,
    ) {
        // $this->middleware('permission:'.Acl::PERMISSION_DEPARTMENT_LIST)->only('index', 'show');
        $this->middleware('permission:'.Acl::PERMISSION_DEPARTMENT_ADD)->only('store');
        $this->middleware('permission:'.Acl::PERMISSION_DEPARTMENT_EDIT)->only('update');
        $this->middleware('permission:'.Acl::PERMISSION_DEPARTMENT_DELETE)->only('destroy');
    }
    /**
     * Get Departments List
     * 
     * Display a listing of the resource.
     * 
     * @authenticated
     * 
     * @response array{
     *      message: string,
     *      data: array<\App\Http\Resources\Api\DepartmentResource>,
     * }
     */
    public function index(Request $request)
    {
        $departments = $this->departmentRepository->all();

        if (!$departments) {
            return $this->errorResponse([], 'Empty department list.', 404);
        }

        return $this->okResponse(
            DepartmentResource::collection($departments),
            'Department list retrieved successfully.',
        );
    }

    /**
     * Create Department
     * 
     * Store a newly created resource in storage.
     * 
     * @authenticated
     * 
     * @response array{
     *      message: string,
     *      data: \App\Http\Resources\Api\DepartmentResource,
     * }
     *
     * @param App\Http\Requests\Department\StoreDepartmentRequest  $request
     */
    public function store(StoreDepartmentRequest $request)
    {
        $department = $this->departmentRepository->create($request->validated());

        return $this->okResponse(
            new DepartmentResource($department),
            'Department created successfully.',
        );
    }

    /**
     * Get Department Detail
     * 
     * Display the specified resource.
     * 
     * @authenticated
     * 
     * @response array{
     *      message: string,
     *      data: \App\Http\Resources\Api\DepartmentResource,
     * }
     * 
     * @param  \App\Models\Department  $department
     */
    public function show(Department $department)
    {
        return $this->okResponse(
            new DepartmentResource($department),
            'Department retrieved successfully.',
        );
    }

    /**
     * Edit Department
     * 
     * Update the specified resource in storage.
     * 
     * @authenticated
     * 
     * @response array{
     *      message: string,
     *      data: \App\Http\Resources\Api\DepartmentResource,
     * }
     * 
     * @param App\Http\Requests\Department\StoreDepartmentRequest  $request
     * @param  \App\Models\Department  $department
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department = $this->departmentRepository->update($department, $request->validated());

        return $this->okResponse(
            new DepartmentResource($department),
            'Department updated successfully.',
        );
    }

    /**
     * Delete Department
     * 
     * Remove the specified resource from storage.
     * 
     * @authenticated
     * 
     * @response array{
     *     message: string,
     *     data: array{},
     * }
     * 
     * @param  \App\Models\Department  $department
     */
    public function destroy(Department $department)
    {
        $deleted = $this->departmentRepository->destroy($department);

        return $deleted
            ? $this->okResponse([], 'Department deleted successfully.')
            : $this->errorResponse([], 'Failed to delete department.', 422);
    }

    /**
     * Get List Users
     * 
     * Get list users of a department.
     * 
     * @authenticated
     * 
     * @response array{
     *      message: string,
     *      data: array<\App\Http\Resources\Api\UserResource>,
     * }
     * 
     * @param  \App\Models\Department  $department
     */
    public function getUsersOfDepartment(Department $department)
    {
        if (!$department) {
            return $this->errorResponse([], 'Department not found.', 404);
        }
        
        $users = $this->departmentRepository->getUsersByDepartmentId($department->id);

        return $this->okResponse(
            UserResource::collection($users),
            'Users retrieved successfully.',
        );
    }
}
