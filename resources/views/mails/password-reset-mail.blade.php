<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #2563eb; /* Tailwind blue-600 */
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .content {
            padding: 30px;
        }
        .content p {
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2563eb;
            color: black;
            font-weight: bold;
            text-decoration: none;
            border-radius: 6px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            padding: 20px;
            background: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hello, {{ $email ?? 'User' }},</p>

            <p>We received a request to reset your password. Click the button below to reset it:</p>

            <p style="text-align: center;">
                <a href="{{ $url }}" class="button">Reset Password</a>
            </p>

            <p>If you didn’t request a password reset, you can safely ignore this email. Your password will not change.</p>

            <p>Thanks,<br>The {{ config('app.name') }} Team</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
