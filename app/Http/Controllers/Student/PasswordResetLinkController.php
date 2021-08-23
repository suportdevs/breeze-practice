<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student; 
use App\Mail\StudentPasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('student.auth.forgot-password');
    }

    /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitForgetPasswordLink(Request $request)
      {
            $request->validate([
                'email' => 'required|email|exists:students',
            ]);
    
            $token = Str::random(64);
    
            DB::table('password_resets')->insert([
                //   'email' => $request->_token, 
                'email' => $request->email, 
                'token' => $token, 
                'created_at' => Carbon::now()
                ]);
            
            $student = DB::table('password_resets')->where('token', $token)->first();
            $url = url(route('student.password.reset', [
                'token' => $student->token,
                'email' => $request->email,
            ], false));
            // Student Auth expire count
            $count = Config::get('student.passwords.'.Config::get('student.defaults.passwords').'.expire', 60);

            Mail::to($request->email)->send(new StudentPasswordResetMail($url, $count));;
    
            return back()->with('status', 'We have e-mailed your password reset link!');
      }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
