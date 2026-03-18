<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Acl\Acl;

/**
 * @tags Categories Management
 */
class CategoryController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_CATEGORY_LIST)->only('index', 'show');
        $this->middleware('permission:' . Acl::PERMISSION_CATEGORY_ADD)->only('store');
        $this->middleware('permission:' . Acl::PERMISSION_CATEGORY_EDIT)->only('update');
        $this->middleware('permission:' . Acl::PERMISSION_CATEGORY_DELETE)->only('destroy');
    }

    /**
     * Get Category List
     * 
     * Display a listing of the resource.
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\CategoryResource,
     *   pagination: array{
     *     current_page: int,
     *     last_page: int,
     *     per_page: int,
     *     total: int
     *   }
     * }
     */
    public function index(Request $request)
    {
        $categories = $this->categoryRepository->serverPaginationFiltering($request->all());
            if (!$categories || $categories->isEmpty()) {
                return $this->errorResponse(
                    [],
                    'No categories found.',
                    404
                );
            }

        return $this->okResponse([
            'categories' => CategoryResource::collection($categories),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
        ], 'Category list retrieved successfully.');
    }

    /**
     * Create Category
     * 
     * Store a newly created resource in storage.
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\CategoryResource,
     * }
     * 
     * @param \App\Http\Requests\Category\StoreCategoryRequest $request
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryRepository->create($request->validated());

        return $category
            ? $this->okResponse(new CategoryResource($category), 'Category created successfully.')
            : $this->errorResponse([], 'Failed to create category.', 422);
    }

    /**
     * Show Category Detail
     * 
     * Display the specified resource.
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\CategoryResource,
     * }
     */
    public function show(Category $category)
    {
        if (!$category) {
            return $this->errorResponse(
                [],
                'Category not found.',
                404
            );
        }

        return $this->okResponse(new CategoryResource($category), 'Category details retrieved successfully.');
    }

    /**
     * Edit Category
     * 
     * Update the specified resource in storage.
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\CategoryResource,
     * }
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updatedCategory = $this->categoryRepository->update($category, $request->validated());

        return $updatedCategory
            ? $this->okResponse(new CategoryResource($updatedCategory), 'Category updated successfully.')
            : $this->errorResponse([], 'Failed to update category.', 422);
    }

    /**
     * Delete Category
     * 
     * Remove the specified resource from storage.
     * 
     * @response array{
     *   message: string,
     *   data: array{},
     * }
     */
    public function destroy(Category $category)
    {
        if (!$category) {
            return $this->errorResponse(
                [],
                'Category not found.',
                404
            );
        }

        $deleted = $this->categoryRepository->destroy($category);

        return $deleted
            ? $this->okResponse([], 'Category deleted successfully.')
            : $this->errorResponse([], 'Failed to delete category.', 422);
    }
}
