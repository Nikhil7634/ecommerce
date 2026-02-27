<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }
        .company-info h2 {
            margin: 0 0 5px 0;
            color: #0d6efd;
        }
        .company-info p {
            margin: 3px 0;
            color: #666;
            font-size: 11px;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h3 {
            margin: 0 0 5px 0;
        }
        .invoice-info p {
            margin: 3px 0;
            font-size: 11px;
        }
        .bill-to {
            margin-bottom: 30px;
        }
        .bill-to h4 {
            margin-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 5px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #f8f9fa;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-size: 12px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }
        .totals {
            text-align: right;
            margin-top: 20px;
        }
        .totals table {
            width: 300px;
            margin-left: auto;
        }
        .totals td {
            padding: 5px 10px;
            border: none;
            font-size: 11px;
        }
        .totals .grand-total {
            font-size: 14px;
            font-weight: bold;
            color: #0d6efd;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #f0f0f0;
            padding-top: 20px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
        }
        .badge-success { background: #d1e7dd; color: #0f5132; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #cff4fc; color: #055160; }
        .badge-danger { background: #f8d7da; color: #842029; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <h2>{{ config('app.name') }}</h2>
                <p>123 Business Street</p>
                <p>City, State 12345</p>
                <p>contact@example.com</p>
                <p>+1 234 567 890</p>
            </div>
            <div class="invoice-info">
                <h3>INVOICE</h3>
                <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                <p><strong>Payment Status:</strong> 
                    {{ ucfirst($order->payment_status) }}
                </p>
                <p><strong>Order Status:</strong> 
                    {{ ucfirst($order->status) }}
                </p>
            </div>
        </div>

        <div class="bill-to">
            <h4>Bill To:</h4>
            <p><strong>{{ $order->shipping_name }}</strong></p>
            <p>{{ $order->shipping_address }}</p>
            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_zip }}</p>
            <p>Phone: {{ $order->shipping_phone }}</p>
            <p>Email: {{ $order->shipping_email }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name ?? $item->product->name }}</strong>
                        @if($item->variant_details)
                            @php $variants = json_decode($item->variant_details, true); @endphp
                            @if(!empty($variants['color'])) <br><small>Color: {{ $variants['color'] }}</small> @endif
                            @if(!empty($variants['size'])) <br><small>Size: {{ $variants['size'] }}</small> @endif
                        @endif
                    </td>
                    <td>₹{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>₹{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Shipping:</td>
                    <td>₹{{ number_format($order->shipping_charge, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax (GST):</td>
                    <td>₹{{ number_format($order->tax_amount, 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Grand Total:</td>
                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This is a system generated invoice. No signature required.</p>
        </div>
    </div>
</body>
</html>