<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    /**
     * Lấy tất cả categories (GET /categories)
     */
    public function getAll(): Collection;

    /**
     * Lấy danh sách phân trang (GET /categories?page=1)
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Tìm category theo ID (GET /categories/{id})
     */
    public function find(int $id): ?Category;

    /**
     * Tạo category mới (POST /categories)
     */
    public function create(array $data): Category;

    /**
     * Cập nhật category (PUT/PATCH /categories/{id})
     */
    public function update(int $id, array $data): Category;

    /**
     * Xóa category (DELETE /categories/{id})
     */
    public function delete(int $id): bool;
}