@extends('emails.layout')

@section('body')
<p style="margin:0 0 12px;">Hello {{ $user->name }},</p>
<p style="margin:0 0 12px;">Welcome to <strong style="color:#e2b342;">IQAB</strong> — your account is ready. You can browse virtual numbers, digital services and Telegram tools from your dashboard.</p>
@if($temporaryPassword)
<p style="margin:0 0 12px;">An administrator created this account for you. Your temporary password is:</p>
<p style="margin:0 0 12px;text-align:center;"><code style="display:inline-block;background:#0a0d13;border:1px solid #d89c2b;color:#eac968;font-size:16px;padding:10px 22px;border-radius:8px;letter-spacing:1px;">{{ $temporaryPassword }}</code></p>
<p style="margin:0 0 12px;">Please log in and change it from your profile as soon as possible.</p>
@endif
<p style="margin:20px 0 0;text-align:center;">
    <a href="{{ route('dashboard') }}" style="display:inline-block;background:#b8861f;color:#ffffff;text-decoration:none;font-weight:bold;font-size:14px;padding:12px 30px;border-radius:8px;">Open Dashboard</a>
</p>
@endsection
