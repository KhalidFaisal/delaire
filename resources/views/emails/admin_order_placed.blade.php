<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Placed - #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333333;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, {{ $portfolio->brand_color_1 ?? '#de2e79' }}, {{ $portfolio->brand_color_2 ?? '#9c124e' }});
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 15px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 8px;
            margin-top: 25px;
            margin-bottom: 15px;
            color: {{ $portfolio->brand_color_1 ?? '#de2e79' }};
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 10px 0;
            font-size: 14px;
            vertical-align: top;
        }
        .details-table td.label {
            font-weight: 600;
            color: #666666;
            width: 140px;
        }
        .details-table td.value {
            color: #111111;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .items-table th {
            background-color: #f8fafc;
            color: #666666;
            font-weight: 600;
            text-align: left;
            padding: 12px;
            font-size: 13px;
            border-bottom: 2px solid #eef2f6;
        }
        .items-table td {
            padding: 12px;
            font-size: 14px;
            border-bottom: 1px solid #eef2f6;
        }
        .text-right {
            text-align: right;
        }
        .summary-wrapper {
            margin-top: 20px;
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 15px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 6px 0;
            font-size: 14px;
        }
        .summary-table tr.total-row td {
            border-top: 1px solid #eef2f6;
            padding-top: 10px;
            font-weight: 700;
            font-size: 16px;
            color: {{ $portfolio->brand_color_1 ?? '#de2e79' }};
        }
        .btn-wrapper {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            background-color: {{ $portfolio->brand_color_1 ?? '#de2e79' }};
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .footer {
            background-color: #f8fafc;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #888888;
            border-top: 1px solid #eef2f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(!empty($portfolio->logo))
                <img src="{{ asset($portfolio->logo) }}" alt="{{ $portfolio->company_name ?? env('APP_NAME', 'Pinkush') }}" style="max-height: 45px; margin-bottom: 10px; vertical-align: middle;">
            @endif
            <h1>New Order Received</h1>
            <p>Order Number: #{{ $order->order_number }}</p>
        </div>
        <div class="content">
            <p style="font-size: 15px; margin-top: 0; line-height: 1.5; color: #555555;">
                Hello Admin, a new order has been placed in the shop. Below are the complete customer and order details.
            </p>

            <div class="section-title">Customer & Shipping Details</div>
            <table class="details-table">
                <tr>
                    <td class="label">Name:</td>
                    <td class="value">{{ $order->shipping_name }}</td>
                </tr>
                <tr>
                    <td class="label">Phone:</td>
                    <td class="value">{{ $order->shipping_phone }}</td>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td class="value">{{ $order->shipping_email }}</td>
                </tr>
                <tr>
                    <td class="label">Address:</td>
                    <td class="value">
                        {{ $order->shipping_address }},<br>
                        {{ $order->shipping_city }} - {{ $order->shipping_zip }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Payment Method:</td>
                    <td class="value">{{ $order->payment_method }}</td>
                </tr>
                @if($order->admin_name)
                <tr>
                    <td class="label">Created By (Admin):</td>
                    <td class="value">{{ $order->admin_name }}</td>
                </tr>
                @endif
                @if($order->reference)
                <tr>
                    <td class="label">Reference:</td>
                    <td class="value">{{ $order->reference }}</td>
                </tr>
                @endif
                @if($order->notes)
                <tr>
                    <td class="label">Order Notes:</td>
                    <td class="value" style="font-style: italic;">"{{ $order->notes }}"</td>
                </tr>
                @endif
            </table>

            <div class="section-title">Order Items</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-right">Price</th>
                        <th class="text-right" style="width: 50px;">Qty</th>
                        <th class="text-right" style="width: 100px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product ? $item->product->pro_title : 'Product Removed' }}</strong>
                            @if($item->size)
                            <br><small style="color: #666;">Size: {{ $item->size }}</small>
                            @endif
                        </td>
                        <td class="text-right">BDT {{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ $item->qty }}</td>
                        <td class="text-right">BDT {{ number_format($item->price * $item->qty, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary-wrapper">
                <table class="summary-table">
                    <tr>
                        <td style="color: #666;">Subtotal:</td>
                        <td class="text-right">BDT {{ number_format($order->subtotal > 0 ? $order->subtotal : ($order->total - $order->delivery_charge + $order->promo_discount), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666;">Delivery Charge:</td>
                        <td class="text-right">BDT {{ number_format($order->delivery_charge, 2) }}</td>
                    </tr>
                    @if($order->promo_discount > 0)
                    <tr>
                        <td style="color: #666;">Discount (Promo: {{ $order->promo_code }}):</td>
                        <td class="text-right">-BDT {{ number_format($order->promo_discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td>Grand Total:</td>
                        <td class="text-right">BDT {{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="btn-wrapper">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn">View Order Details</a>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated notification. Please do not reply directly to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $portfolio->company_name ?? env('APP_NAME', 'Pinkush') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
