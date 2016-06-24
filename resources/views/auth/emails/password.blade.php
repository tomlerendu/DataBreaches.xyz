<h1>DataBreaches.xyz</h1>
<p>Click the link below to reset your password:</p>
<p>
    <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">
        {{ $link }}
    </a>
</p>
