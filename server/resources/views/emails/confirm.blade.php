<!DOCTYPE html>
<html>

<head>
    <title>Confirm Your Email Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #0f0f0f;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 50px auto;
            border: 1px solid #ddd;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .email-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .email-body {
            margin-top: 30px;
        }

        .email-container p {
            font-size: 16px;
            color: #0f0f0f;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            color: #fff;
            background-color: #000;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0f0f0f;
        }



        .btn:hover::before {
            left: 100%;
        }

        .email-container .emoji {
            font-size: 48px;
            margin: 10px 0;
        }

        .salutation {
            margin-top: 20px;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #88888*;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="emoji"></div>
        <h1>Confirm Your Email</h1>

        <p>Hi there! 👋</p>

        <div class="email-body">
            <p>Thank you for joining <strong>Maasai Market Online</strong>. We're excited to have you! 🌍✨</p>

            <p>To complete your registration, please verify your email address by clicking the button below:</p>

            <p>
                <a href="{{ $verificationUrl }}" class="btn">Verify Email</a>
            </p>
        </div>

        <div class="salutation">
            <p>Thank you for choosing Maasai Market Online! ❤</p>

            <p>Warm regards,<br><strong>Maasai Market Online</strong></p>
        </div>


        <div class="footer">
            If you did not sign up for Maasai Market Online, please ignore this email.
        </div>
    </div>
</body>

</html>
