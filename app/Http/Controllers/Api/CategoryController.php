<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    /**
     * Get all categories
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = $this->repository->getAll();
        return CategoryResource::collection($categories);
    }

    /**
     * Create new category
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->repository->create($request->validated());

        return response()->json([
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category)
        ], 201);
    }

    /**
     * Get category by ID
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->repository->find($id);

        return response()->json([
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Update category
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->repository->update($id, $request->validated());

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Delete category
     */
    public function destroy(int $id): JsonResponse
    {
        $this->repository->delete($id);

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}