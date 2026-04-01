<?php

namespace App\Notifications;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewIdeaNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $idea;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Idea $idea)
    {
        $this->user = $user;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'idea_id' => $this->idea->id,
            'message' => "New idea submitted by {$this->user->name}: {$this->idea->title}",
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Idea Submitted')
            ->line("A new idea has been submitted by {$this->user->name}.")
            ->line("Title: {$this->idea->title}")
            ->action('View Idea', url("/ideas/{$this->idea->id}"))
            ->line('Thank you for using UniIdeas!');
    }
}
