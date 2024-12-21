<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total-row {
            font-weight: bold;
            background-color: #e8f4e8;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="https://res.cloudinary.com/kwishi/image/upload/v1728280942/Maasai-market_rlsbf1.svg" alt="Maasai Market"
            class="logo">
        <h1>Thank you for your order, {{ $order->user->name }}!</h1>
    </div>

    <p>Your order (Reference: <strong>{{ $order->reference }}</strong>) has been successfully placed.</p>

    <h2>Order Details</h2>
    @if ($order->orderItems && $order->orderItems->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price (Each)</th>
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
                    <td colspan="3">Total Price</td>
                    <td>${{ number_format($order->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    <p>If you have any questions, contact us at <a
            href="mailto:support@maasaimarketonline.com">support@maasaimarketonline.com</a>.</p>
</body>

</html>
