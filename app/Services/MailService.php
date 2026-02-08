<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Acl\Acl;
use App\Models\Idea;
use App\Models\User;

class MailService
{
    /**
     * Send a generic mailable to all managers.
     *
     * @param array $data
     * @return void
     */
    public function notifyManagers(array $data): void
    {
        $emails = User::role(Acl::ROLE_QA_COORDINATOR)->pluck('email')->filter()->unique();

        foreach ($emails as $email) {
            Mail::to($email)->send(new SendMail($data));
        }
    }

    /**
     * Send a generic mailable to a single user (by id).
     *
     * @param int $userId
     * @param array $data
     * @return void
     */
    public function notifyUser(int $userId, array $data): void
    {
        $user = User::find($userId);
        if (! $user || ! $user->email) {
            return;
        }

        Mail::to($user->email)->send(new SendMail($data));
    }

    /**
     * Send a mail to author when their idea have a new comment by any users.
     *
     * @param int $ideaId
     * @param array $data
     * @return void
     */
    public function notifyIdeaAuthorNewComment(int $ideaId, array $data): void
    {
        $idea = Idea::find($ideaId);
        if (! $idea || ! $idea->user_id) {
            return;
        }

        $this->notifyUser($idea->user_id, $data);
    }
}
