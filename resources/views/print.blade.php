<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #{{ $order->id }} Receipt</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            width: 350px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
        }
        .info, .items, .totals {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            font-size: 14px;
        }
        .items table th,
        .items table td {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .totals table td {
            padding: 3px 0;
        }
        .center {
            text-align: center;
        }
        @media print {
            body { width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Soshie Buh</h2>
        <p>Muntinlupa City<br>09121212121</p>
    </div>

    <div class="info">
        <strong>Order ID:</strong> {{ $order->id }} <br>
        <strong>Date:</strong> {{ $order->created_at->format('Y-m-d h:i A') }} <br>
        <strong>Cashier:</strong> {{ $order->user->name }} <br>
        <strong>Customer:</strong> {{ $order->customer_name ?? 'Guest' }}
    </div>

    <div class="items">
        <table>
            <thead>
                <tr>
                    <th align="left">Item</th>
                    <th align="center">Qty</th>
                    <th align="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="center">x{{ $item->quantity }}</td>
                        <td align="right">
                            ₱{{ number_format($item->quantity * $item->price, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <table>
            <tr>
                <td><strong>Amount Paid:</strong></td>
                <td align="right">₱{{ number_format($order->amount_paid, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Change:</strong></td>
                <td align="right">₱{{ number_format($order->change, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td align="right"><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="center">
        <p>Thank you for your purchase!</p>
    </div>

    <script>
        window.onload = () => {
            window.print();
        };
    </script>

</body>
</html>
