<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
            color: #0f0f0f;
        }

        .email-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            color: #0f0f0f;
            margin-bottom: 30px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 20px;
        }

        .order-status {
            background-color: #faf9f6;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0f0f0f;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            background-color: #009e60;
            color: white;
            border-radius: 20px;
            font-weight: bold;
        }

        .message {
            color: #0f0f0f;
            margin: 20px 0;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ccc;
            color: #777;
            font-size: 0.9em;
        }

        .emoji-icon {
            font-size: 1.5em;
            margin-right: 5px;
            vertical-align: middle;
        }

        .company-name {
            color: #0f0f0f;
            font-weight: bold;
        }

        .section {
            margin: 25px 0;
            padding: 20px;
            background-color: #faf9f6;
            border-radius: 8px;
            border-left: 4px solid #0f0f0f;
        }

        .section-title {
            color: #0f0f0f;
            font-size: 1.2em;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        .status-message {
            background-color: #faf9f6;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .address-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .address-item {
            display: flex;
            flex-direction: row;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 5px;
        }

        .address-label {
            color: #0f0f0f;
            font-size: 0.9em;
            min-width: fit-content;
        }

        .address-value {
            color: #0f0f0f;
            margin-right: 5px;
        }

        a {
            color: #009e60;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1><span class="emoji-icon"></span> Order Status Update</h1>
        </div>

        <div class="message">
            <p><span class="emoji-icon"></span> Hello {{ $order->user->name ?? $order->guest_name }},</p>

            <div class="order-status">
                <p>Order ID: #{{ $order->id }}</p>
                <p>Order Reference: {{ $order->reference }}</p>
                <p>Current Status: <span class="status-badge">{{ $order->status }}</span></p>
            </div>

            <div class="section">
                <h2 class="section-title"><span class="emoji-icon">📍</span> Shipping Address</h2>
                <div class="address-grid">
                    <div class="address-item">
                        <p class="address-label">Address:<span> {{ $order->shippingAddress->address1 }}</span></p>
                    </div>
                    <div class="address-item">
                        <p class="address-label">City:<span> {{ $order->shippingAddress->city }}</span></p>
                    </div>
                    <div class="address-item">
                        <p class="address-label">State:<span> {{ $order->shippingAddress->state }}</span></p>
                    </div>
                    <div class="address-item">
                        <p class="address-label">Country:<span> {{ $order->shippingAddress->country }}</span></p>
                    </div>
                    <div class="address-item">
                        <p class="address-label">Postal Code:<span> {{ $order->shippingAddress->postal_code }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="status-message">
                @if ($order->status == 'Processing')
                    <p><span class="emoji-icon">⚙️</span> We're currently processing your order.</p>
                @elseif($order->status == 'Shipped')
                    <p><span class="emoji-icon">🚚</span> Your order is on its way!</p>
                @elseif($order->status == 'Delivered')
                    <p><span class="emoji-icon">✅</span> Your order has been delivered!</p>
                @endif

                <p><span class="emoji-icon"></span> We'll keep you updated on any changes to your order status.</p>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for shopping with us! <span class="emoji-icon"></span></p>
            <p>Best regards,</p>
            <p class="company-name">Maasai Market Online</p>
            <p><small>If you have any questions, please don't hesitate to <a
                        href="mailto:support@maasaimarketonline.com">contact us.</a></small></p>
        </div>
    </div>
</body>

</html>
