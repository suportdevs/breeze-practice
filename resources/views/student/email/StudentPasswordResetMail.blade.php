@component('mail::message')
# Hello 

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => url($url)])
Reset Password
@endcomponent

This password reset link will expire in {{ $count }} minutes.

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}<br><br>

<hr>
<br>
If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <a href="{{ url($url) }}">{{ url($url) }}</a>

@endcomponent

