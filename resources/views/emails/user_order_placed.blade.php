<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Placed Successfully - #{{ $order->order_number }}</title>
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
            background: linear-gradient(135deg, {{ $portfolio->brand_color_1 ?? '#de2e79' }}, {{ $portfolio->brand_color_2 ?? '#f75d9c' }});
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .thank-you {
            font-size: 18px;
            font-weight: 600;
            color: {{ $portfolio->brand_color_1 ?? '#de2e79' }};
            margin-top: 0;
            margin-bottom: 15px;
        }
        .intro-text {
            font-size: 15px;
            line-height: 1.6;
            color: #555555;
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 6px;
            margin-top: 25px;
            margin-bottom: 15px;
            color: #333333;
            letter-spacing: 0.5px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-grid td {
            font-size: 14px;
            line-height: 1.5;
            padding: 5px 0;
            vertical-align: top;
        }
        .info-grid td.label {
            color: #666666;
            font-weight: 600;
            width: 120px;
        }
        .info-grid td.value {
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
            padding: 10px 12px;
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
        .attachment-note {
            margin-top: 25px;
            background-color: #fff9fb;
            border: 1px dashed {{ $portfolio->brand_color_1 ?? '#de2e79' }};
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 13px;
            color: {{ $portfolio->brand_color_3 ?? '#b8235a' }};
            display: flex;
            align-items: center;
        }
        .footer {
            background-color: #f8fafc;
            text-align: center;
            padding: 25px;
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
                <img src="{{ asset($portfolio->logo) }}" alt="{{ $portfolio->company_name ?? env('APP_NAME', 'Pinkush') }}" style="max-height: 50px; margin-bottom: 15px; vertical-align: middle;">
            @endif
            <h1>Thank You for Your Order!</h1>
            <p>Order Number: #{{ $order->order_number }}</p>
        </div>
        <div class="content">
            <h3 class="thank-you">Hi {{ $order->shipping_name }},</h3>
            <p class="intro-text">
                Your order has been placed successfully and is now being processed. We will notify you once your package is on its way.
            </p>

            <div class="section-title">Order Information</div>
            <table class="info-grid" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="label">Date:</td>
                    <td class="value">{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Payment:</td>
                    <td class="value">{{ $order->payment_method }} (Cash on Delivery)</td>
                </tr>
                <tr>
                    <td class="label">Address:</td>
                    <td class="value">
                        {{ $order->shipping_address }},<br>
                        {{ $order->shipping_city }} - {{ $order->shipping_zip }}
                    </td>
                </tr>
            </table>

            <div class="section-title">Items Ordered</div>
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

            <div class="attachment-note">
                <strong>Note:</strong> We have attached your official Invoice PDF to this email for your records.
            </div>
        </div>
        <div class="footer">
            <p>If you have any questions, please contact us at {{ $portfolio->email ?? 'support@pinkush.com' }} @if(!empty($portfolio->contact_number)) or call {{ $portfolio->contact_number }} @endif.</p>
            <p>&copy; {{ date('Y') }} {{ $portfolio->company_name ?? env('APP_NAME', 'Pinkush') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
