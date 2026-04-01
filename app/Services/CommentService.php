<?php

namespace App\Services;

use App\Enum\SubmissionStatus;
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
use App\Repositories\Ideas\IdeaRepositoryInterface;
use App\Repositories\Submission\SubmissionRepositoryInterface;

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
        protected SubmissionRepositoryInterface $submissionRepository,
        protected IdeaRepositoryInterface $ideaRepository,
    ) {
        //
    }

    /**
     * Override create method.
     */
    public function create(array $data): ?Comment
    {
        $idea = $this->ideaRepository->getIdeaById($data['idea_id']);
        
        $submission = $idea->submission;
        if ($submission->status === SubmissionStatus::FINALLY_CLOSED) {
            return null;
        }

        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $comment = $this->commentRepository->create($data);
            $comment->idea()->increment('total_comments');
            
            $ideaOwner = $comment->idea->user ?? null;

            if ($ideaOwner && $ideaOwner->id !== auth()->id()) {
                $ideaOwner->notify(new NewCommentIdeaNotification(auth()->user(), $comment, $comment->idea));
                // NotifyCommentIdeaJob::dispatch($comment, $comment->idea);
            }

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
        if ($submission->status === SubmissionStatus::FINALLY_CLOSED) {
            return null;
        }

        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();

            $this->commentRepository->update($comment, $data);

            DB::commit();
            return $comment;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update Comment Failed: ' . $e->getMessage());
            return null;
        }
    }
}
