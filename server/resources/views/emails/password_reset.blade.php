<!DOCTYPE html>
<html>

<head>
    <title>Password Reset</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
            color: #0f0f0f;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            color: #0f0f0f;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 200px;
            height: auto;
        }

        h1 {
            color: #0f0f0f;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            text-align: center;
        }

        .reset-link {
            display: inline-block;
            background-color: #000;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }

        .reset-link:hover {
            background-color: #0f0f0f;
        }

        .disclaimer {
            color: #777;
            font-size: 0.9em;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="https://res.cloudinary.com/kwishi/image/upload/v1734847185/Maasai-market_phlqzq.png"
                alt="Company Logo" />
        </div>
        <h1>Password Reset Link</h1>
        <p>We received a request to reset your password. Click the button below to create a new password:</p>
        <div style="text-align: center;">
            <a href="{{ $resetLink }}" class="reset-link">Reset Password</a>
        </div>
        <p class="disclaimer">If you didn't request a password reset, please ignore this email. This link will expire in
            24 hours for security reasons.</p>
    </div>
</body>

</html>
