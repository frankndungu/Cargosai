<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
</head>

<body>
    <h1>Your order status has been updated!</h1>
    <p>Hello {{ $order->user->name }},</p>
    <p>Your order (ID: {{ $order->id }}) status has been updated to: <strong>{{ $order->status }}</strong>.</p>
    <p>We will notify you once your order has been shipped or delivered.</p>
    <p>Thank you for shopping with us!</p>
    <p>Best regards,</p>
    <p>Maasai Market Online</p>
</body>

</html>
