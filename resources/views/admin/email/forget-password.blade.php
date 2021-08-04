<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>
        <h3>Hello</h3>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <a class="btn btn-dark" href="{{ route('admin.password.reset', $token) }}">Reset Password</a>
        <p>This password reset link will expire in 60 minutes.</p>
        <p>If you did not request a password reset, no further action is required.</p>
        <p>Regards, </p>
        <p>Laravel</p>
        <hr>
        <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: {{ route('admin.password.reset', $token) }}</p>
    
</body>
</html>
        

        