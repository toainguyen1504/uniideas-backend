<?php

namespace App\Services;

use App\Acl\Acl;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Jobs\NotifyIdeaModeratorsJob;
use App\Models\Idea;
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
    public function create($data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['title'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            $data['user_id'] = auth()->id();

            $idea = $this->ideaRepository->create($data);

            if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
                $idea->addMedia($data['file_path'])
                    ->usingFileName($data['file_path']->getClientOriginalName())
                    ->toMediaCollection($idea::FILE_PATH_COLLECTION);

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
    public function update(Idea $idea, array $data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['title'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            if (isset($data['user_id']) && $data['user_id'] !== $idea->user_id) {
                $data['user_id'] = auth()->id();
            }

            if (isset($data['file_path']) && $data['file_path'] instanceof UploadedFile) {
                $idea->clearMediaCollection($idea::FILE_PATH_COLLECTION);
                $idea->addMedia($data['file_path'])
                    ->usingFileName($data['file_path']->getClientOriginalName())
                    ->toMediaCollection($idea::FILE_PATH_COLLECTION);

                $idea->load('media');
            }

            $idea->update($data);

            DB::commit();            
            return $idea;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Idea Failed: ' . $e->getMessage());
            return null;
        }
    }
}
