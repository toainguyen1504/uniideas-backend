<?php

namespace App\Services;

use App\Acl\Acl;
use App\Enum\IdeaFilter;
use App\Enum\IdeaStatus;
use App\Enum\ReactEnum;
use App\Enum\SubmissionStatus;
use App\Http\Resources\Api\IdeaRankingResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Jobs\NotifyIdeaModeratorsJob;
use App\Models\Idea;
use App\Models\Submission;
use App\Notifications\NewIdeaNotification;
use App\Repositories\Ideas\IdeaRepositoryInterface;
use App\Repositories\Submission\SubmissionRepositoryInterface;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;



class IdeaService
{
    /**
     * Summary of __construct
     *
     * @param UserRepositoryInterface $userRepository
     * @param IdeaRepositoryInterface $ideaRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected IdeaRepositoryInterface $ideaRepository,
        protected SubmissionRepositoryInterface $submissionRepository,
    ) {
        //
    }

    /**
     * Override create method to return Idea
     */
    public function create(array $data): ?Idea
    {
        $submissionId = $data['submission_id'] ?? null;
        $submission = $this->submissionRepository->getSubmissionWithStatus($submissionId);

        if ($submission->status !== SubmissionStatus::OPEN) {
            return null;
        }

        try {
            DB::beginTransaction();

            $data['slug'] = Str::slug($data['title'] ?? '');
            $data['user_id'] = auth()->id();
            $data['terms_conditions'] = Arr::get($data, 'terms_conditions', false);
            $data['is_featured'] = false;
            $data['status'] = IdeaStatus::PENDING->value;

            $idea = $this->ideaRepository->create($data);
            $idea->load('submission');

            if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
                $file = $data['file_path'];
                
                $titleSlug = $data['slug'];
                $timestamp = now()->timestamp;
                $extension = $file->getClientOriginalExtension();
                $newFileName = "{$titleSlug}-{$timestamp}.{$extension}";

                $idea->addMedia($file)
                    ->usingFileName($newFileName)
                    ->toMediaCollection(Idea::FILE_PATH_COLLECTION);
                
                $idea->load('media');
            }

            $departmentId = auth()->user()->department_id ?? null;
            $qaCoordinators = $this->userRepository->getUsersByQACoordinatorRole($departmentId);
            $qaCoordinators = $qaCoordinators->reject(function ($user) {
                return $user->id === auth()->id();
            });

            if ($qaCoordinators->isNotEmpty()) {
                Notification::send(
                    $qaCoordinators,
                    new NewIdeaNotification(auth()->user(), $idea)
                );
            }

            NotifyIdeaModeratorsJob::dispatch($idea);

            DB::commit();

            return $idea;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create Idea Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Override update method to return Idea
     */
    public function update($model, $data)
    {
        $submissionId = $data['submission_id'] ?? null;
        if ($submissionId) {
            $submission = $this->submissionRepository->getSubmissionWithStatus($submissionId);
            if ($submission->status !== SubmissionStatus::OPEN) {
                return null;
            }
        } else {
            $submission = $model->submission ?? $this->submissionRepository->find($model->submission_id);
            if ($submission && $submission->status !== SubmissionStatus::OPEN) {
                return null;
            }
        }

        try {
            DB::beginTransaction();

            if (isset($data['title'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            if (isset($data['user_id']) && $data['user_id'] !== $model->user_id) {
                $data['user_id'] = auth()->id();
            }

            if (isset($data['terms_conditions'])) {
                $data['terms_conditions'] = (bool) $data['terms_conditions'];
            }

            if (isset($data['is_featured'])) {
                $data['is_featured'] = (bool) $data['is_featured'];
            }

            if (isset($data['status']) && $data['status'] !== $model->status) {
                $data['status'] = IdeaStatus::PENDING->value;
            }

            if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
                $file = $data['file_path'];
                
                $titleSlug = $data['slug'] ?? $model->slug;
                $timestamp = now()->timestamp;
                $extension = $file->getClientOriginalExtension();
                $newFileName = "{$titleSlug}-{$timestamp}.{$extension}";

                $model->clearMediaCollection($model::FILE_PATH_COLLECTION);

                $model->addMedia($file)
                    ->usingFileName($newFileName)
                    ->toMediaCollection($model::FILE_PATH_COLLECTION);

                $model->load('media');
            }

            $model->update($data);

            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Idea Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get top 3 ideas have is_featured = true in a submission and have most court likes.
     */
    public function getTopFeaturedIdeas($submissionId, int $limit = 3)
    {
        $submission = $this->submissionRepository->find($submissionId);
        if (! $submission || ! $submission->is_closed) {
            return null;
        }

        return $this->ideaRepository->getTopFeaturedIdeas($submissionId, $limit);
    }

    public function getIdeasByFilter(IdeaFilter $filter, ?int $perPage = null)
    {
        $query = Idea::withCount([
            'reacts as likes_count' => fn($q) => $q->where('react', ReactEnum::LIKE),
            'reacts as dislikes_count' => fn($q) => $q->where('react', ReactEnum::DISLIKE),
            'comments as comments_count'
        ])->where('status', IdeaStatus::APPROVED->value);

        switch ($filter) {
            case IdeaFilter::POPULAR:
                $query->orderByRaw('likes_count - dislikes_count DESC')
                    ->orderByDesc('views')
                    ->orderByDesc('created_at');
                break;

            case IdeaFilter::VIEWED:
                $query->orderByDesc('views')
                    ->orderByRaw('likes_count - dislikes_count DESC')
                    ->orderByDesc('created_at');
                break;

            case IdeaFilter::LATEST:
            default:
                $query->orderByDesc('created_at');
                break;
        }

      return $query->paginate($perPage ?? 5);
    }

    /**
     * Approve idea by Coordinator
     */
    public function approve($model, $data)
    {
        try {
            DB::beginTransaction();

            $model->update($data);

            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve Idea Failed: ' . $e->getMessage());
            return null;
        }
    }
}
