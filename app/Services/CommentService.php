<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use App\Jobs\NotifyCommentIdeaJob;
use App\Models\Comment;
use App\Notifications\NewCommentIdeaNotification;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\Idea;

class CommentService
{
    /**
     * Summary of __construct
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected CommentRepositoryInterface $commentRepository,
    ) {
        //
    }

    /**
     * Override create method.
     */
    public function create(array $data): ?Comment
    {
        $idea = Idea::findOrFail($data['idea_id']);
        $submission = $idea->submission;

        if (!$submission->status->canComment()) {
            return null;
        }

        DB::beginTransaction();
        try {
            $data['user_id'] = auth()->id();
            $comment = $this->commentRepository->create($data);

            NotifyCommentIdeaJob::dispatch($comment, $idea);

            DB::commit();
            return $comment;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Create Comment Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Override update method.
     */
    public function update(Comment $comment, array $data): ?Comment
    {
        $submission = $comment->idea->submission;

        if (!$submission->status->canBeModified()) {
            return null; // chặn khi read-only
        }

        DB::beginTransaction();
        try {
            $data['user_id'] = auth()->id();
            $this->commentRepository->update($comment, $data);

            NotifyCommentIdeaJob::dispatch($comment, $comment->idea);

            DB::commit();
            return $comment;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update Comment Failed: ' . $e->getMessage());
            return null;
        }
    }
}
