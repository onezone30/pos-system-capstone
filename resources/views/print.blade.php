<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #{{ $order->id }} Receipt</title>

    <style>
        /* Base Styles for Receipt Printer */
        body {
            font-family: 'Consolas', 'Courier New', monospace; /* Monospace font for better alignment */
            padding: 10px;
            width: 320px; /* Standard thermal printer width */
            margin: 0 auto;
            color: #111;
        }

        /* Helper Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mt-3 { margin-top: 15px; }
        .mb-2 { margin-bottom: 10px; }
        .divider { 
            border-top: 1px dashed #333; 
            margin: 10px 0; 
            height: 1px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 12px;
            margin: 2px 0;
        }

        /* Order Info */
        .info {
            font-size: 13px;
            line-height: 1.6;
        }
        .info strong {
            display: inline-block;
            width: 80px;
        }

        /* Items Table */
        .items table {
            width: 100%;
            font-size: 13px;
            border-collapse: collapse;
        }
        .items table th {
            padding: 5px 0;
            font-weight: bold;
            border-bottom: 2px solid #000; /* Thicker line for header */
            text-transform: uppercase;
        }
        .items table td {
            padding: 3px 0;
        }
        .item-name {
            max-width: 180px; /* Limit name width */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Totals */
        .totals table {
            width: 100%;
            font-size: 14px;
        }
        .totals table td {
            padding: 5px 0;
        }
        .totals table tr:last-child td {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000;
        }
        .currency {
            font-weight: normal;
        }

        /* Footer */
        .footer p {
            margin: 15px 0 0;
            font-size: 12px;
            text-transform: uppercase;
        }

        /* Print Specific Styles */
        @media print {
            body { 
                width: 100%; 
                margin: 0;
                padding: 0;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Soshie Buh</h2>
        <p>Muntinlupa City</p>
        <p>0912-121-2121</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <div style="display: flex; justify-content: space-between;">
            <strong>Order ID:</strong> <span>#{{ $order->id }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <strong>Date:</strong> <span>{{ $order->created_at->format('Y-m-d h:i A') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <strong>Cashier:</strong> <span>{{ $order->user->name }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <strong>Customer:</strong> <span>{{ $order->customer_name ?? 'Guest' }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="items">
        <table>
            <thead>
                <tr>
                    <th align="left">Item</th>
                    <th align="center">Qty</th>
                    <th align="right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td class="item-name">{{ $item->product->name }}</td>
                        <td class="text-center">x{{ $item->quantity }}</td>
                        <td class="text-right">
                            <span class="currency">₱</span>{{ number_format($item->quantity * $item->price, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="divider"></div>

    <div class="totals">
        <table>
            <tr>
                <td style="width: 70%;">Total Amount:</td>
                <td class="text-right">₱{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Amount Paid:</td>
                <td class="text-right">₱{{ number_format($order->amount_paid, 2) }}</td>
            </tr>
            <tr>
                <td class="mt-3">Change Due:</td>
                <td class="text-right mt-3">₱{{ number_format($order->change, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="footer text-center">
        <p>Thank you for your purchase!</p>
        <p style="font-style: italic; margin-top: 5px;">{{ config('app.name') }} POS</p>
    </div>

    <script>
        window.onload = () => {
            window.print();
        };
    </script>

</body>
</html>