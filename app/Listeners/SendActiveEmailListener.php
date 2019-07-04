<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendActiveEmailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param User $user
     *
     * @return void
     */
    public function handle(User $user)
    {
        if ($user->is_active) {
            return;
        }

        $url = route("confirm_email", [$user->id, $user->activation_token]);
        \Mail::send('emails.confirm', compact('user', 'url'), function (\Illuminate\Mail\Message $message) use ($user) {
            $message->to($user->email)->subject("注册激活邮件");
        });
    }
}
