@extends('emails.layout')

@section('body')
<p style="margin:0 0 12px;">Hello {{ $user->name }},</p>
<p style="margin:0 0 16px;">A virtual number has been assigned to your IQAB account by our support team:</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0d13;border:1px solid #262b33;border-radius:8px;margin-bottom:16px;">
    <tr>
        <td style="padding:14px 18px;">
            <div style="font-family:monospace;font-size:20px;font-weight:bold;color:#ffffff;">{{ $purchase->phoneNumber?->phone_number ?? 'N/A' }}</div>
            <div style="font-size:13px;color:#9aa2ad;margin-top:4px;">{{ $purchase->phoneNumber?->country ?? '' }}</div>
        </td>
        <td align="right" style="padding:14px 18px;font-size:13px;color:#eac968;font-weight:bold;white-space:nowrap;">{{ number_format($purchase->price, 2) }} {{ currency() }}</td>
    </tr>
</table>
<p style="margin:0 0 12px;font-size:13px;color:#9aa2ad;">You can view it anytime under Dashboard → My Numbers, and open its inbox to receive SMS.</p>
<p style="margin:20px 0 0;text-align:center;">
    <a href="{{ route('my-numbers.show', $purchase) }}" style="display:inline-block;background:#b8861f;color:#ffffff;text-decoration:none;font-weight:bold;font-size:14px;padding:12px 30px;border-radius:8px;">View My Number</a>
</p>
@endsection
