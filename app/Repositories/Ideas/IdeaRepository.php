<?php

namespace App\Repositories\Ideas;

use App\Models\Idea;
use App\Models\Submission;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Jobs\NotifyIdeaModeratorsJob;
use App\Notifications\NewIdeaNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class IdeaRepository extends BaseRepository implements IdeaRepositoryInterface
{
    const ITEM_PER_PAGE = 5;

    public function __construct(Idea $model)
    {
        parent::__construct($model);
    }

    /**
     * Phân trang + filter theo search params
     */
    public function serverPaginationFiltering(array $searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::ITEM_PER_PAGE);

        $query = $this->applyFilters($searchParams)
            ->orderBy(
                Arr::get($searchParams, 'sort_by', 'created_at'),
                Arr::get($searchParams, 'sort_order', 'desc')
            );

        return $query->paginate($limit);
    }

    /**
     * Áp dụng filter cho query
     */
    private function applyFilters(array $searchParams)
    {
        $query = $this->model->newQuery()->with(['user', 'category', 'submission']);

        if ($keyword = Arr::get($searchParams, 'search')) {
            $query->where(
                fn($q) =>
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%")
            );
        }

        if ($status = Arr::get($searchParams, 'status')) {
            $query->where('status', $status);
        }

        if ($categoryId = Arr::get($searchParams, 'category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($submissionId = Arr::get($searchParams, 'submission_id')) {
            $query->where('submission_id', $submissionId);
        }

        return $query;
    }

    /**
     * Lấy tất cả ideas đã được duyệt
     */
    public function getAllApproved(): Collection
    {
        return $this->model->where('status', 'approved')->get();
    }

    public function getBySubmissionId(int $submissionId): Collection
    {
        return $this->model->where('submission_id', $submissionId)->get();
    }

    public function getByCategoryId(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    /**
     * Tạo mới idea kèm file upload
     */
    public function createWithFile(array $data, $file = null): Idea
    {
        if ($file) {
            $data['file_path'] = $file->store('uploads');
        }
        return $this->create($data);
    }

    /**
     * Cập nhật idea kèm file upload
     */
    public function updateWithFile(int $id, array $data, $file = null): ?Idea
    {
        $idea = $this->find($id);

        if (! $idea) {
            return null; // hoặc throw Exception
        }

        if ($file) {
            $data['file_path'] = $file->store('uploads');
        }

        return $this->update($idea, $data);
    }

    /**
     * Xóa idea
     */
    public function destroy($model): bool
    {

        $idea = $model instanceof Idea ? $model : $this->find($model);

        if (!$idea) return false;

        $submission = $idea->submission;

        if (!$submission->status->canBeModified()) {
            return false;
        }
        return parent::destroy($idea);
    }

    /**
     * Override create method
     */
    public function create($data): ?Idea
    {
        return Idea::create($data);
    }


    /**
     * Override update method to return Idea
     */
    public function update($model, $data)
    {
        $submission = $model->submission;

        if (!$submission->canBeModified()) {
            return null;
        }
        DB::beginTransaction();
        try {
            if (isset($data['title'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            if (isset($data['user_id']) && $data['user_id'] !== $model->user_id) {
                $data['user_id'] = auth()->id();
            }

            if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
                $model->clearMediaCollection($this->model::FILE_PATH_COLLECTION);
                $model->addMedia($data['file_path'])
                    ->usingFileName($data['file_path']->getClientOriginalName())
                    ->toMediaCollection($this->model::FILE_PATH_COLLECTION);
                $model->load('media');
            }

            $model->update($data);

            NotifyIdeaModeratorsJob::dispatch($model);

            DB::commit();
            return $model;
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
