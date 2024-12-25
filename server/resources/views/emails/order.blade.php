<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            background-color: #f5f5f5;
            color: #0f0f0f;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 30px 0;
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .logo {
            max-width: 300px;
            height: auto;
            margin-bottom: 20px;
        }

        .welcome-text {
            font-size: 28px;
            color: #0f0f0f;
            margin: 0;
            font-weight: 600;
        }

        .order-reference {
            background-color: #fff;
            color: #0f0f0f;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #0f0f0f;
        }

        .section {
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 20px;
            color: #0f0f0f;
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            font-weight: 600;
        }

        .address-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .address-item {
            margin-bottom: 15px;
        }

        .address-label {
            font-size: 14px;
            color: #666;
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .address-value {
            font-size: 16px;
            color: #0f0f0f;
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 12px;
            text-align: left;
            border-top: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .total-section {
            margin-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .total-row {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .total-row td {
            padding: 15px 12px;
        }

        .contact-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 12px;
        }

        .contact-link {
            color: #0f0f0f;
            text-decoration: none;
            font-weight: 500;
        }

        .contact-link:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
            margin-top: 30px;
        }

        /* Add styling for order status */
        .order-status {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            background-color: #e8f5e9;
            color: #2e7d32;
            margin-top: 10px;
        }

        /* Add some cool hover effects */
        .section:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://res.cloudinary.com/kwishi/image/upload/v1734847185/Maasai-market_phlqzq.png"
                alt="Maasai Market" class="logo">
            <h1 class="welcome-text">Thank you for your order, {{ $order->user->name }}!</h1>
            <div class="order-status">Order Confirmed</div>
        </div>

        <div class="order-reference">
            <strong>Order Reference:</strong> {{ $order->reference }}
            <br>
            <strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}
        </div>

        <div class="section">
            <h2 class="section-title">Shipping Address</h2>
            <div class="address-grid">
                <div class="address-item">
                    <span class="address-label">Address</span>
                    <span class="address-value">{{ $order->shippingAddress->address1 }}</span>
                </div>
                <div class="address-item">
                    <span class="address-label">City</span>
                    <span class="address-value">{{ $order->shippingAddress->city }}</span>
                </div>
                <div class="address-item">
                    <span class="address-label">State</span>
                    <span class="address-value">{{ $order->shippingAddress->state }}</span>
                </div>
                <div class="address-item">
                    <span class="address-label">Country</span>
                    <span class="address-value">{{ $order->shippingAddress->country }}</span>
                </div>
                <div class="address-item">
                    <span class="address-label">Postal Code</span>
                    <span class="address-value">{{ $order->shippingAddress->postal_code }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Order Details</h2>
            @if ($order->orderItems && $order->orderItems->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Product' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="3">Shipping Fee</td>
                            <td>${{ number_format($order->shipping_fee, 2) }}</td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="3"><strong>Total Price</strong></td>
                            <td><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>

        <div class="contact-section">
            <p>Need help? Contact our support team at <a href="mailto:support@maasaimarketonline.com"
                    class="contact-link">support@maasaimarketonline.com</a></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Maasai Market Online. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
