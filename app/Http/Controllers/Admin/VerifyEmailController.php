<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Auth;
use App\Models\Admin;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if(Auth::guard('admin')->check()){
            $user = Admin::where('email', $request->email)->first();
            dd($user);
            if(! $user){
                return redirect()->route('admin.register')->with('errors', 'Invalied URL');
            }
            else{
                if($user->email_verified_at){
                    return redirect()->route('admin.dashboad')->with("status", 'Email already verifed.');
                }
                else{
                    $user->update([
                        'email_verified_at'=>Carbon\Carbon::now()
                    ]);
                    return redirect()->route('admin.dashboad')->with("status", 'Email verifed successful.');
                }
            }
            // if ($request->user()->hasVerifiedEmail()) {
            //     return redirect()->intended(RouteServiceProvider::ADMINHOME.'?verified=1');
            // }

            // if ($request->user()->markEmailAsVerified()) {
            //     event(new Verified($request->user()));
            // }

            return redirect()->intended(RouteServiceProvider::ADMINHOME.'?verified=1');
        }
    }
}
