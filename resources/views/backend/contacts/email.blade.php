<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .original-message {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .original-message h3 {
            margin-top: 0;
            color: #667eea;
            font-size: 16px;
        }
        .reply-message {
            margin: 20px 0;
            white-space: pre-wrap;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .divider {
            height: 1px;
            background: #dee2e6;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Response to Your Message</p>
        </div>

        <div class="content">
            <p>Dear {{ $contactMessage->name }},</p>

            <p>Thank you for contacting us. We have received your message and here is our response:</p>

            <div class="divider"></div>

            <div class="reply-message">
{{ $reply }}
            </div>

            <div class="divider"></div>

            <div class="original-message">
                <h3>Your Original Message:</h3>
                <p style="margin: 0; white-space: pre-wrap;">{{ $contactMessage->message }}</p>
                <p style="margin: 10px 0 0 0; font-size: 12px; color: #6c757d;">
                    Sent on {{ $contactMessage->created_at->format('F d, Y \a\t h:i A') }}
                </p>
            </div>

            <p>If you have any further questions or concerns, please don't hesitate to reach out to us.</p>

            <a href="mailto:{{ config('mail.from.address') }}" class="btn">Contact Us Again</a>
        </div>

        <div class="footer">
            <p>This email was sent by {{ config('app.name') }}</p>
            <p>
                <a href="{{ config('app.url') }}">Visit Our Website</a> |
                <a href="mailto:{{ config('mail.from.address') }}">Contact Support</a>
            </p>
            <p style="margin-top: 15px; color: #999;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
