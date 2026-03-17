<?php

namespace App\Services;

use App\Acl\Acl;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Jobs\NotifyIdeaModeratorsJob;
use App\Models\Idea;
use App\Models\Submission;
use App\Notifications\NewIdeaNotification;
use App\Repositories\Ideas\IdeaRepositoryInterface;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class IdeaService
{
    /**
     * Summary of __construct
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected IdeaRepositoryInterface $ideaRepository,
    ) {
        //
    }

    /**
     * Override create method to return Idea
     */
   public function create(array $data): ?Idea
{
    $submission = Submission::findOrFail($data['submission_id']);

    if (!$submission->canAcceptIdeas()) {
        return null;
    }

    DB::beginTransaction();
    try {
        $data['slug'] = Str::slug($data['title'] ?? '');
        $data['user_id'] = auth()->id();

        $idea = $this->ideaRepository->create($data);
        if (!$idea) {
            DB::rollBack();
            return null;
        }

        if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
            $idea->addMedia($data['file_path'])
                ->usingFileName($data['file_path']->getClientOriginalName())
                ->toMediaCollection(Idea::FILE_PATH_COLLECTION);
            $idea->load('media');
        }

        NotifyIdeaModeratorsJob::dispatch($idea);

        DB::commit();
        return $idea;
    } catch (\Throwable $e) {
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
            Log::error('Update Idea Failed: ' . $e->getMessage());
            return null;
        }
    }
}
