<?php

namespace App\Services;

use App\Acl\Acl;
use App\Enum\SubmissionStatus;
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

            $idea = $this->ideaRepository->create($data);

            if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
                $idea->addMedia($data['file_path'])
                    ->usingFileName($data['file_path']->getClientOriginalName())
                    ->toMediaCollection(Idea::FILE_PATH_COLLECTION);
                $idea->load('media');
            }

            $qaCoordinators = $this->userRepository->getUsersByQACoordinatorRole();
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

            if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
                $model->clearMediaCollection($this->model::FILE_PATH_COLLECTION);
                $model->addMedia($data['file_path'])
                    ->usingFileName($data['file_path']->getClientOriginalName())
                    ->toMediaCollection($this->model::FILE_PATH_COLLECTION);
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
    public function listIdeas(array $filters)
    {
        // Có thể thêm logic nghiệp vụ ở đây nếu cần
        return $this->ideaRepository->serverPaginationFiltering($filters);
    }
}
