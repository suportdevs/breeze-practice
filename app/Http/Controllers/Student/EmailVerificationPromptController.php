<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Auth;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        if(Auth::guard('student')->check()){
            return $request->user()->hasVerifiedEmail()
                        ? redirect()->intended(RouteServiceProvider::STUDENTHOME)
                        : view('student.auth.verify-email');
        }
    }
}
