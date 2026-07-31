@php
    $status = ucfirst($order->status);
    
    // Status configurations
    $statusColor = '#3b82f6'; // Blue for Processing
    $statusBgGradient = 'linear-gradient(135deg, #3b82f6, #1d4ed8)';
    $statusMessage = 'Your order has been accepted and is currently being processed. We are preparing your items for shipment.';
    $statusTitle = 'Order Accepted & Processing';
    $stepIndex = 1; // 0: Pending, 1: Processing, 2: Shipped, 3: Delivered

    if ($status === 'Pending') {
        $statusColor = '#f59e0b'; // Amber
        $statusBgGradient = 'linear-gradient(135deg, #f59e0b, #d97706)';
        $statusMessage = 'Your order is pending confirmation. We will verify the details shortly.';
        $statusTitle = 'Order Pending';
        $stepIndex = 0;
    } elseif ($status === 'Shipped') {
        $statusColor = '#8b5cf6'; // Purple
        $statusBgGradient = 'linear-gradient(135deg, #8b5cf6, #6d28d9)';
        $statusMessage = 'Excellent news! Your order has been shipped and is on its way to your shipping address.';
        $statusTitle = 'Order Shipped';
        $stepIndex = 2;
    } elseif ($status === 'Delivered') {
        $statusColor = '#10b981'; // Green
        $statusBgGradient = 'linear-gradient(135deg, #10b981, #047857)';
        $statusMessage = 'Your order has been successfully delivered. We hope you enjoy your purchase!';
        $statusTitle = 'Order Delivered';
        $stepIndex = 3;
    } elseif ($status === 'Cancelled') {
        $statusColor = '#ef4444'; // Red
        $statusBgGradient = 'linear-gradient(135deg, #ef4444, #b91c1c)';
        $statusMessage = 'Please note that your order has been cancelled by the administrator. Any stock reserved has been restored.';
        $statusTitle = 'Order Cancelled';
        $stepIndex = -1; // Non-standard step
    }
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $statusTitle }} - #{{ $order->order_number }}</title>
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
            background: {!! $statusBgGradient !!};
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
        .status-callout {
            background-color: #f8fafc;
            border-left: 4px solid {{ $statusColor }};
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .status-callout h4 {
            margin: 0 0 5px 0;
            font-size: 15px;
            color: #111111;
            font-weight: 700;
        }
        .status-callout p {
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
            color: #555555;
        }
        
        /* Status Tracker Progress Bar */
        .tracker-container {
            margin: 30px 0;
            padding: 0 10px;
            text-align: center;
        }
        .tracker {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin: 0 auto;
        }
        .tracker-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }
        .tracker-step .step-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: #e2e8f0;
            display: inline-block;
            position: relative;
            z-index: 2;
            border: 3px solid #ffffff;
            box-shadow: 0 0 0 1px #cbd5e1;
        }
        .tracker-step.completed .step-dot {
            background-color: {{ $statusColor }};
            box-shadow: 0 0 0 1px {{ $statusColor }};
        }
        .tracker-step.active .step-dot {
            background-color: #ffffff;
            border: 4px solid {{ $statusColor }};
            box-shadow: 0 0 0 1px {{ $statusColor }};
        }
        .tracker-step .step-line {
            position: absolute;
            top: 9px;
            left: 50%;
            width: 100%;
            height: 3px;
            background-color: #e2e8f0;
            z-index: 1;
        }
        .tracker-step.completed .step-line {
            background-color: {{ $statusColor }};
        }
        .tracker-step:last-child .step-line {
            display: none;
        }
        .tracker-step .step-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-top: 8px;
        }
        .tracker-step.completed .step-label,
        .tracker-step.active .step-label {
            color: #0f172a;
            font-weight: 700;
        }

        .section-title {
            font-size: 14px;
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
            <h1>{{ $statusTitle }}</h1>
            <p>Order Number: #{{ $order->order_number }}</p>
        </div>
        <div class="content">
            <h3 style="margin-top: 0; color: #111111;">Dear {{ $order->shipping_name }},</h3>
            
            <div class="status-callout">
                <h4>Status Update Details</h4>
                <p>{{ $statusMessage }}</p>
            </div>

            @if($stepIndex >= 0)
            <!-- Visual Progress Tracker -->
            <div class="tracker-container">
                <div class="tracker">
                    <div class="tracker-step {{ $stepIndex > 0 ? 'completed' : ($stepIndex == 0 ? 'active' : '') }}">
                        <span class="step-dot"></span>
                        <span class="step-line"></span>
                        <span class="step-label">Pending</span>
                    </div>
                    <div class="tracker-step {{ $stepIndex > 1 ? 'completed' : ($stepIndex == 1 ? 'active' : '') }}">
                        <span class="step-dot"></span>
                        <span class="step-line"></span>
                        <span class="step-label">Accepted</span>
                    </div>
                    <div class="tracker-step {{ $stepIndex > 2 ? 'completed' : ($stepIndex == 2 ? 'active' : '') }}">
                        <span class="step-dot"></span>
                        <span class="step-line"></span>
                        <span class="step-label">Shipped</span>
                    </div>
                    <div class="tracker-step {{ $stepIndex == 3 ? 'completed active' : '' }}">
                        <span class="step-dot"></span>
                        <span class="step-line"></span>
                        <span class="step-label">Delivered</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="section-title">Order Information</div>
            <table class="info-grid" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="label">Current Status:</td>
                    <td class="value">
                        <strong style="color: {{ $statusColor }};">{{ $statusTitle }}</strong>
                    </td>
                </tr>
                <tr>
                    <td class="label">Date Ordered:</td>
                    <td class="value">{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Payment Method:</td>
                    <td class="value">{{ $order->payment_method }} (Cash on Delivery)</td>
                </tr>
                <tr>
                    <td class="label">Shipping Address:</td>
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
                <strong>Invoice Attachment:</strong> We have attached the updated Invoice PDF matching your order's latest state to this email.
            </div>
        </div>
        <div class="footer">
            <p>If you have any questions, please contact us at {{ $portfolio->email ?? 'support@pinkush.com' }} @if(!empty($portfolio->contact_number)) or call {{ $portfolio->contact_number }} @endif.</p>
            <p>&copy; {{ date('Y') }} {{ $portfolio->company_name ?? env('APP_NAME', 'Pinkush') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
