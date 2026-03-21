<?php

namespace App\Services;

use App\Models\Idea;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    /**
     * Summary of __construct
     */
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
    ) {
        //
    }

    public function destroy($model)
    {
        try {
            DB::beginTransaction();
            $category = $this->categoryRepository->find($model->id);

            $linkedIdeas = Idea::where('category_id', $category->id)->exists();
            if ($linkedIdeas) {
                DB::rollBack();
                return false;
            }

            $category->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete Category Failed: ' . $e->getMessage());
            return false;
        }
    }
}
