<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Idea;
use App\Services\MailService;

class NotifyIdeaModeratorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $idea;
    /**
     * Create a new job instance.
     */
    public function __construct(Idea $idea)
    {
        $this->idea = $idea;
    }

    /**
     * Execute the job.
     */
    public function handle(MailService $mailService): void
    {
        $idea = $this->idea->loadMissing('user');
        if (! $idea) {
            return;
        }

        $content = [
            'title' => "New idea has been created.",
            'body' => "The idea titled '{$idea->title}' created by {$idea->user->name}.",
        ];

        $mailService->notifyManagers([
            'email' => null,
            'content' => $content,
        ]);
    }
}
