<?php

namespace App\Jobs;

use App\Models\Comment;
use App\Models\Idea;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyCommentIdeaJob implements ShouldQueue
{
    use Queueable;

    protected $comment;
    protected $idea;

    /**
     * Create a new job instance.
     */
    public function __construct(Comment $comment, Idea $idea)
    {
        $this->comment = $comment;
        $this->idea = $idea;
    }

    /**
     * Execute the job.
     */
    public function handle(MailService $mailService): void
    {
        $comment = $this->comment->loadMissing('user');
        $idea = $this->idea->loadMissing('user');

        $content = [
            'title' => "New comment from {$comment->user->name} on your idea.",
            'body' => "User {$comment->user->name} commented on your idea titled '{$idea->title}': '{$comment->content}'",
        ];

        $mailService->notifyIdeaAuthorNewComment($idea->id, [
            'email' => $idea->user->email,
            'content' => $content,
        ]);
    }
}
