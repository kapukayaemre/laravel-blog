<h1>Verify Email</h1>

<p>
    Hello {{ $user->name }}, welcome.
</p>

<p>
    Please click the below link to verify your account. <br>
</p>

<a href="{{ route("verify-token", ['token' => $token]) }}">
    Verify Me
</a>
