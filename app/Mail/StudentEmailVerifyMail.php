<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class StudentEmailVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    // public function studentEmailUrl($user){

    //     $url = URL::temporarySignedRoute(
    //         'student.verification.verify',
    //         Carbon::now()->addMinutes(Config::get('student.verification.expire', 60)),
    //         [
    //             'id' => $user->id,
    //             'hash' => sha1($user->email),
    //         ]
    //     );
    //     return $this->build($url);
    // }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('student.email.StudentEmailVerifyMail')->with([
            'user' => $this->user,
            'url' => $this->url,

        ]);
    }
}
