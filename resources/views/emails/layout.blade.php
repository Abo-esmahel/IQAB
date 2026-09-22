<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'IQAB' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#0a0d13;font-family:Arial,Helvetica,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0a0d13;padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background-color:#161a21;border:1px solid #262b33;border-radius:12px;overflow:hidden;">
                <tr>
                    <td style="padding:28px 32px 0;text-align:center;">
                        <div style="font-size:26px;font-weight:bold;color:#e2b342;letter-spacing:1px;">IQAB</div>
                        <div dir="rtl" style="font-size:15px;color:#9aa2ad;margin-top:4px;">رؤيةٌ لا تُخطئُ</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px 32px 8px;color:#dfe2e6;font-size:14px;line-height:1.7;">
                        @yield('body')
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 32px 28px;text-align:center;color:#727a86;font-size:12px;border-top:1px solid #262b33;">
                        IQAB · Virtual Numbers &amp; Digital Services<br>
                        <span style="color:#565d68;">This is an automated message, please do not reply.</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
