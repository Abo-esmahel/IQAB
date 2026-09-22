@extends('emails.layout')

@section('body')
<p style="margin:0 0 12px;">Hello {{ $user->name }},</p>
@if($serviceRequest->status->value === 'completed')
<p style="margin:0 0 12px;">Good news — your <strong style="color:#e2b342;">{{ $serviceRequest->telegramService?->name }}</strong> request for <code style="background:#0a0d13;padding:2px 8px;border-radius:6px;color:#ffffff;">{{ $serviceRequest->target_identifier }}</code> has completed successfully.</p>
<p style="margin:20px 0 0;text-align:center;">
    <a href="{{ route('telegram.result', $serviceRequest) }}" style="display:inline-block;background:#b8861f;color:#ffffff;text-decoration:none;font-weight:bold;font-size:14px;padding:12px 30px;border-radius:8px;">View Result</a>
</p>
@else
<p style="margin:0 0 12px;">Your <strong style="color:#e2b342;">{{ $serviceRequest->telegramService?->name }}</strong> request for <code style="background:#0a0d13;padding:2px 8px;border-radius:6px;color:#ffffff;">{{ $serviceRequest->target_identifier }}</code> could not be completed.</p>
@if($serviceRequest->error_message)
<p style="margin:0 0 12px;font-size:13px;color:#f87171;">Reason: {{ $serviceRequest->error_message }}</p>
@endif
<p style="margin:0;font-size:13px;color:#9aa2ad;">Please try again or contact our support on Telegram and we will take care of it.</p>
@endif
@endsection
