<!DOCTYPE html>
<html>

<head>
    <title>New Order Notification</title>
</head>

<body>
    <h1>New Order Placed</h1>
    <p>A customer has placed a new order on Maasai Market Online.</p>
    <p><strong>Customer Name:</strong> {{ $order->user->name }}</p>
    <p><strong>Order Reference:</strong> {{ $order->reference }}</p>
    <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
    <p>Please check the admin dashboard for more details.</p>
</body>

</html>
