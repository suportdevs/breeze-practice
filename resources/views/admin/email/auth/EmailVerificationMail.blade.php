@component('mail::message')
Hello Admin {{$user->name}}
Please click the button below to verify your email address.

@component('mail::button', ['url' => url('admin/verify-email',$user->id,$user->token)])
Verify your Email
@endcomponent

If you did not create an account, no further action is required.

Regards,
{{ config('app.name') }}

<hr>

If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: {{ url('admin/verify-email',$user->id,$user->token) }}
@endcomponent
