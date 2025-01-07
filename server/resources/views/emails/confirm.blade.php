<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} - Confirm Your Email Address</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #0f0f0f;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            overflow: hidden;
        }

        .header {
            background: #f9f9f9;
            padding: 32px 24px;
            text-align: center;
        }

        .header img {
            width: 300px;
            height: auto;
            margin-bottom: 16px;
        }

        .header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .content {
            padding: 32px 24px;
            text-align: center;
        }

        .welcome-text {
            font-size: 18px;
            color: #0f0f0f;
            margin-bottom: 24px;
        }

        .btn {
            display: inline-block;
            padding: 16px 32px;
            background-color: #000;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 24px 0;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background-color: #333333;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .divider {
            height: 1px;
            background-color: #e5e5e5;
            margin: 32px 0;
        }

        .footer {
            padding: 24px;
            background-color: #f8f8f8;
            text-align: center;
            color: #666666;
            font-size: 14px;
        }

        .social-links {
            margin-top: 24px;
        }

        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #666666;
            text-decoration: none;
        }

        .social-links a:hover {
            color: #1a1a1a;
        }

        .signature {
            margin: 24px 0;
            font-weight: 500;
            color: #1a1a1a;
        }

        .disclaimer {
            font-size: 13px;
            color: #888888;
            margin-top: 16px;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 24px 16px;
            }

            .content {
                padding: 24px 16px;
            }

            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <img src="https://res.cloudinary.com/kwishi/image/upload/v1734847185/Maasai-market_phlqzq.png"
                alt="{{ config('app.name') }} Logo" />
        </div>

        <div class="content">
            <p class="welcome-text">
                Welcome to {{ config('app.name') }}! 🌍
            </p>

            <p>
                Hi {{ $user->name ?? 'there' }} 👋,
            </p>

            <p>
                Thank you for joining our community. We're thrilled to have you here and can't wait to show you
                authentic African crafts and culture.
            </p>

            <p>
                To get started, please verify your email address by clicking the button below:
            </p>

            <a href="{{ $verificationUrl }}" class="btn">
                Confirm Your Email Address
            </a>

            <p>
                This link will expire in {{ config('auth.verification.expire', 24) }} hours for security reasons.
            </p>

            <div class="divider"></div>

            <div class="signature">
                With warm regards,<br>
                <strong>{{ config('app.name') }} ❤</strong>
            </div>
        </div>

        <div class="footer">
            @if (config('social.links'))
                <div class="social-links">
                    @foreach (config('social.links') as $platform => $url)
                        <a href="{{ $url }}">{{ ucfirst($platform) }}</a>
                        @if (!$loop->last)
                            •
                        @endif
                    @endforeach
                </div>
            @endif

            <p>
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>

            <p class="disclaimer">
                If you didn't create an account with {{ config('app.name') }}, please ignore this email or <a
                    href="mailto:support@maasaimarketonline.com" style="color: #666666;">contact our support team</a> if
                you have
                concerns.
            </p>
        </div>
    </div>
</body>

</html>
