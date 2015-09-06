Hello {!! $user->username !!},
<br><br>
You have requested to have your password reset for your account at {!! Config::get('app.app_name') !!}.
<br><br>
Click here to reset your password: {{ url('password/reset/'.$token) }}
<br><br>
This password reset token will expire in {!! Config::get('auth.password.expire') !!} minutes.
<br><br>
If you received this email in error, you can safely ignore this email.