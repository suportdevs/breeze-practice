<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class StudentPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $count;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $count)
    {
        $this->url = $url;
        $this->count = $count;
    }

    // public function ResetPasswordUrl ($token)
    // {
    //     $student = DB::table('password-reset')->where('token', $token)->first();
    //     // Reset Password Link  
        

    //     $this->build($url, $count);
    // }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('student.email.StudentPasswordResetMail')->with([
            'url' => $this->url,
            'count' => $this->count
        ]);
    }
}
