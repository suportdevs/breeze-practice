<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Mail\StudentEmailVerifyMail;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // dd(Auth::guard("student")->check());
        return view('student.auth.register');
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
            'email' => 'required|string|email|max:255|unique:students',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'token' => $request->token,
            'password' => Hash::make($request->password),
        ]);

        $url = URL::temporarySignedRoute(
            'student.verification.verify',
            Carbon::now()->addMinutes(Config::get('student.verification.expire', 60)),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        // event(new Registered($user));
        Mail::to($request->email)->send(new StudentEmailVerifyMail($user, $url));

        Auth::guard('student')->login($user);

        return redirect(RouteServiceProvider::STUDENTHOME);
    }

    function studentEmailVerify(EmailVerificationRequest $request)
    {
        
        $student = Student::where('id', $request->id)->first();
        // dd($dadmin->email);
        // dd(password_verify($admin->email, $request->hash));
        $email = sha1($student->email);

        if($email !== $request->hash && $request->id){
            return redirect()->route('student.register');
        }
        elseif($email == $request->hash && $request->id){
            $student->update([
                'email_verified_at'=> Carbon::now()
            ]);
            return redirect()->route('student.dashboard')->with("status", 'Email verifed successful.');
        }
    }
}
