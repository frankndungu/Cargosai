<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Pending</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h1 {
            color: #000;
            border-bottom: 2px solid #0f0f0f;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .order-reference {
            background-color: #f8f9fa;
            border-left: 4px solid #0f0f0f;
            padding: 10px;
            margin: 15px 0;
            font-weight: bold;
        }

        .signature {
            margin-top: 20px;
            color: #7f8c8d;
            font-style: italic;
        }

        .support-note {
            font-size: 0.9em;
            color: #6c757d;
            margin-top: 20px;
            border-top: 1px solid #e9ecef;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Order Pending</h1>

        <p>Dear {{ $order->user->name ?? $order->guest_name }},</p>

        <p>We regret to inform you that your order with reference number:</p>

        <div class="order-reference">
            {{ $order->reference }}
        </div>

        <p>Could not be processed at this time due to a pending payment status.</p>

        <p>Please check your payment details and try again.</p>

        <div class="support-note">
            If you continue to experience issues, feel free to contact our support team for assistance.
        </div>

        <p class="signature">
            Thank you for your understanding.<br>
            Best regards,<br>
            {{ config('app.name') }}
        </p>
    </div>
</body>

</html>
