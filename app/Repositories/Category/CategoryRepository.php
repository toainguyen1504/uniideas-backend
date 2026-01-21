<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Enums\CategoryStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly Category $category
    ) {}

    /**
     * Lấy tất cả categories
     */
    public function getAll(): Collection
    {
        return $this->category->all();
    }

    /**
     * Lấy danh sách phân trang
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->category->paginate($perPage);
    }

    /**
     * Tìm category theo ID
     */
    public function find(int $id): ?Category
    {
        return $this->category->find($id);
    }

    /**
     * Tạo category mới
     */
    public function create(array $data): Category
    {
        return $this->category->create($data);
    }

    /**
     * Cập nhật category
     */
    public function update(int $id, array $data): Category
    {
        $category = $this->find($id);
        
        if (!$category) {
            throw new \Exception("Category not found", 404);
        }

        $category->update($data);
        return $category;
    }

    /**
     * Xóa category
     */
    public function delete(int $id): bool
    {
        $category = $this->find($id);
        
        if (!$category) {
            throw new \Exception("Category not found", 404);
        }

        return $category->delete();
    }
}