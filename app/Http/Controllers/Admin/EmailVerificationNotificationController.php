<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Mail;
use App\Mail\EmailVerificationMail;
use App\Models\Admin;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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
        if(Auth::guard('admin')->check()){
            $user = Admin::where('email', $request->user()->email)->first();
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(RouteServiceProvider::ADMINHOME);
            }
            
            $url = URL::temporarySignedRoute(
                'admin.verification.verify',
                Carbon::now()->addMinutes(Config::get('admin.verification.expire', 60)),
                [
                    'id' => $user->id,
                    'hash' => sha1($user->email),
                ]
                );

            // $request->user()->sendEmailVerificationNotification();
            Mail::to($user->email)->send(new EmailVerificationMail($user, $url));

            return back()->with('status', 'verification-link-sent');
        }
    }
}