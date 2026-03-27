<?php

namespace App\Repositories\Ideas;

use App\Enum\ReactEnum;
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
        $query = $this->model->newQuery()->with([
            'user',
            'category',
            'submission',
            'reacts',
            'comments'
        ]);

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
     * Get idea by id
     */
    public function getIdeaById($ideaId)
    {
        return $this->model->findOrFail($ideaId);
    }

    /**
     * Get top 3 ideas have is_featured = true in a submission and have most court likes.
     */
    public function getTopFeaturedIdeas($submissionId, int $limit = 3)
    {
        $query = $this->model->where('submission_id', $submissionId)
            ->with(['media'])
            ->withCount([
                'reacts as total_likes' => function ($q) {
                    $q->where('react', ReactEnum::LIKE->value);
                },
                'comments as comments_count'
            ])
            ->orderByDesc('total_likes')
            ->orderByDesc('created_at')
            ->orderByDesc('total_views')
            ->limit($limit);

        $topIdeas = $query->get();

        $topIds = $topIdeas->pluck('id')->toArray();

        DB::transaction(function () use ($submissionId, $topIds) {
            $this->model->where('submission_id', $submissionId)
                ->update(['is_featured' => false]);

            if (!empty($topIds)) {
                $this->model->whereIn('id', $topIds)
                    ->update(['is_featured' => true]);
            }
        });

        foreach ($topIdeas as $idea) {
            $idea->is_featured = in_array($idea->id, $topIds, true);
        }

        return $topIdeas;
    }
}
