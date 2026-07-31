<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: {{ $portfolio->brand_color_1 ?? '#de2e79' }};
        }
        .invoice-details {
            float: right;
            text-align: right;
        }
        .billing-info {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="float: left;">
            @php
                $showLogo = false;
                if (!empty($portfolio->logo) && file_exists(public_path($portfolio->logo))) {
                    $imagePath = public_path($portfolio->logo);
                    $mimeType = mime_content_type($imagePath);
                    $isWebp = (strpos($mimeType, 'webp') !== false || strpos($portfolio->logo, '.webp') !== false);
                    if (!$isWebp || function_exists('imagecreatefromwebp')) {
                        $showLogo = true;
                        $imageData = base64_encode(file_get_contents($imagePath));
                    }
                }
            @endphp
            @if($showLogo)
                <img src="data:{{ $mimeType }};base64,{{ $imageData }}" style="max-height: 45px; vertical-align: middle;">
            @else
                <span class="logo">{{ $portfolio->company_name ?? env('APP_NAME', 'Pinkush') }}</span>
            @endif
            <div style="font-size: 12px; margin-top: 5px;">
                @if(!empty($portfolio->address))
                    {!! nl2br(e($portfolio->address)) !!} <br>
                @endif
                @if(!empty($portfolio->contact_number))
                    Phone: {{ $portfolio->contact_number }}
                @endif
            </div>
        </div>
        <div class="invoice-details">
            <strong>Invoice #{{ $order->order_number }}</strong><br>
            Date: {{ $order->created_at->format('Y-m-d') }}
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="billing-info">
        <h3>Billing & Shipping To:</h3>
        <p>
            <strong>Name:</strong> {{ $order->shipping_name }}<br>
            <strong>Phone:</strong> {{ $order->shipping_phone }}<br>
            <strong>Email:</strong> {{ $order->shipping_email }}<br>
            <strong>Address:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }} - {{ $order->shipping_zip }}
        </p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product ? $item->product->pro_title : 'Product Removed' }}</td>
                <td>BDT {{ number_format($item->price, 2) }}</td>
                <td>{{ $item->qty }}</td>
                <td>BDT {{ number_format($item->price * $item->qty, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                <td>{{ number_format($order->subtotal > 0 ? $order->subtotal : ($order->total - $order->delivery_charge + $order->promo_discount), 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right"><strong>Delivery Charge:</strong></td>
                <td>{{ number_format($order->delivery_charge, 2) }}</td>
            </tr>
            @if($order->promo_discount > 0)
            <tr>
                <td colspan="3" class="text-right"><strong>Discount ({{ $order->promo_code }}):</strong></td>
                <td>-{{ number_format($order->promo_discount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                <td><strong>BDT {{ number_format($order->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <p>Thank you for shopping with us!</p>

    @if(!empty($portfolio->return_policy))
    <div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; font-size: 10px; text-align: justify; line-height: 1.4;">
        <strong>Return Policy:</strong><br>
        @php
            $cleanText = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '</div>', '</li>'], "\n", $portfolio->return_policy));
            $lines = array_filter(array_map('trim', explode("\n", $cleanText)));
        @endphp
        @foreach($lines as $line)
            • {{ $line }}<br>
        @endforeach
    </div>
    @endif


</body>
</html>
