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
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $data['idea_id'] = Arr::get($data, 'idea_id');

            $comment = $this->commentRepository->create($data);

            if ($comment->idea->user_id !== auth()->id()) {
                Notification::send($comment->idea->user, new NewCommentIdeaNotification(auth()->user(), $comment, $comment->idea));
                NotifyCommentIdeaJob::dispatch($comment, $comment->idea);
            }

            DB::commit();

            return $comment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create Comment Failed: ' . $e->getMessage());
            return null;
        }
    }
    /**
     * Override update method.
     */
    public function update(Comment $comment, $data)
    {
        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $data['idea_id'] = $comment->idea_id;

            $comment->update($data);

            DB::commit();

            return $comment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Comment Failed: ' . $e->getMessage());
            return null;
        }
    }
}
