<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:6px;">
        <h2 style='font-size:16px;'>Welcome {{ $user->name }}</h2>

        <p>
            Thank you for registering with <strong>Exam Manager</strong>.
            Please confirm your email address to activate your account.
        </p>

        <p style="text-align:center; margin:30px 0;">
            <a href="{{ $url }}"
               style="background:#2563eb; color:#ffffff; padding:12px 20px;
                      text-decoration:none; border-radius:4px; display:inline-block;">
                Confirm My Email
            </a>
        </p>

        <p>If you did not create this account, you can safely ignore this email.</p>

        <p style="margin-top:30px;">
            Regards,<br>
            Exam Manager Team.
        </p>
    </div>
</body>
</html>
