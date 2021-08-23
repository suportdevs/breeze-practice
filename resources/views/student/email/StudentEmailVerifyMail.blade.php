

@component('mail::message')
Hello 


Please click the button below to verify your email address.

@component('mail::button', ['url' => url($url)])
Verify your Email
@endcomponent

If you did not create an account, no further action is required.

Regards,
{{ config('app.name') }}


<hr>


If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: <a href="{{ url($url) }}">{{ url($url) }}</a>
@endcomponent
