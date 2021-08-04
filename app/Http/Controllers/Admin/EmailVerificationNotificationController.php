<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Mail;
use App\Mail\EmailVerificationMail;
use App\Models\Admin;

class EmailVerificationNotificationController extends Controller
{
    // public $user;

    // public function __construct($user)
    // {
    //     $this->user = $user;
    // }
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::ADMINHOME);
        }
        $user = Admin::where('email', $request->email)->first();

        // $request->user()->sendEmailVerificationNotification();
        // Mail::to($request->email)->send(new EmailVerificationMail($request->user));

        Mail::to($request->email)->send(new EmailVerificationMail($user));

        return back()->with('status', 'verification-link-sent');
    }
}
