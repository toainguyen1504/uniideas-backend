<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewCommentIdeaNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $comment;
    protected $idea;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Comment $comment, Idea $idea)
    {
        $this->user = $user;
        $this->comment = $comment;
        $this->idea = $idea;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Comment on Idea')
            ->line("{$this->user->name} has commented on the idea '{$this->idea->title}'.")
            ->line("Comment: " . Str::limit($this->comment->content, 100));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'idea_id' => $this->idea->id,
            'message' => "{$this->user->name} has commented on the idea '{$this->idea->title}': " . Str::limit($this->comment->content, 50),
        ];
    }
}
