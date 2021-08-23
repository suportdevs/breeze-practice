<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Mail\StudentEmailVerifyMail;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Mail;
use Carbon\Carbon;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Auth::guard('student')->check()){
            $user = Student::where('email', $request->user()->email)->first();
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(RouteServiceProvider::STUDENTHOME);
            };
            $url = URL::temporarySignedRoute(
                'student.verification.verify',
                Carbon::now()->addMinutes(Config::get('student.verification.expire', 60)),
                [
                    'id' => $user->id,
                    'hash' => sha1($user->email),
                ]
                );

            // $request->user()->sendEmailVerificationNotification();
            Mail::to($user->email)->send(new StudentEmailVerifyMail($user, $url));

            return back()->with('status', 'verification-link-sent');
        }
    }
}
