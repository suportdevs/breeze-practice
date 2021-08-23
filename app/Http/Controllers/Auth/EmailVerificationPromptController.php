<?php

namespace App\Http\Controllers\Auth;

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
        if(Auth::guard('web')->check()){
            return $request->user()->hasVerifiedEmail()
                        ? redirect()->intended(RouteServiceProvider::HOME)
                        : view('auth.verify-email');
        }
    }
}
