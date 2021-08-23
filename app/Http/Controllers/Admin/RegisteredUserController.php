<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'token' => $request->token,
            'password' => Hash::make($request->password),
        ]);

        $url = URL::temporarySignedRoute(
            'admin.verification.verify',
            Carbon::now()->addMinutes(Config::get('admin.verification.expire', 60)),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
            );

        // event(new Registered($user));
        Mail::to($request->email)->send(new EmailVerificationMail($user, $url));

        Auth::guard('admin')->login($user);

        return redirect(RouteServiceProvider::ADMINHOME);
    }

    function adminverify(EmailVerificationRequest $request)
    {   
        
        $admin = Admin::where('id', $request->id)->first();
        // dd($dadmin->email);
        // dd(password_verify($admin->email, $request->hash));
        $email = sha1($admin->email);
        if($email !== $request->hash && $request->id){
            return redirect()->route('admin.register');
        }
        elseif($email == $request->hash && $request->id){
            $admin->update([
                'email_verified_at'=>\Carbon\Carbon::now()
            ]);
            return redirect()->route('admin.dashboard')->with("status", 'Email verifed successful.');
        }
    }

}
