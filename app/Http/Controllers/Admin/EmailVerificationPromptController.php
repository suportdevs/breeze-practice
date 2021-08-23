<?php

namespace App\Http\Controllers\Admin;

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
        if(Auth::guard('admin')->check()){
            return $request->user()->hasVerifiedEmail()
                        ? redirect()->intended(RouteServiceProvider::ADMINHOME)
                        : view('admin.auth.verify-email');
        }
        
    }
}
