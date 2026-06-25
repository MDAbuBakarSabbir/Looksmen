<div style="padding: 30px; font-family: 'Arial', sans-serif;">
    <table width="100%">
        <tr>
            <td><h2>Village Life</h2><p>আপনার গ্রামের খাঁটি পণ্য</p></td>
            <td align="right">
                <h3>INVOICE</h3>
                <p>অর্ডার আইডি: #{{ $order->id }}</p>
                <p>তারিখ: {{ $order->created_at->format('d M, Y') }}</p>
            </td>
        </tr>
    </table>

    <hr>

    <table width="100%" style="margin-top: 20px;">
        <tr>
            <td>
                <strong>বিলের ঠিকানা:</strong><br>
                {{ $order->name }}<br>
                {{ $order->phone }}<br>
                {{ $order->address }}, {{ $order->district }}
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 30px; border-collapse: collapse;">
        <thead>
            <tr style="background: #f4f4f4;">
                <th style="padding: 10px; border: 1px solid #ddd;">পণ্য</th>
                <th style="padding: 10px; border: 1px solid #ddd;">পরিমাণ</th>
                <th style="padding: 10px; border: 1px solid #ddd;">মোট</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $detail)
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $detail->product->title ?? 'N/A' }}</td>
                <td align="center" style="padding: 10px; border: 1px solid #ddd;">{{ $detail->product_qty }}</td>
                <td align="right" style="padding: 10px; border: 1px solid #ddd;">৳ {{ $detail->product_qty * ($detail->product->new_price ?? 0) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" align="right" style="padding: 10px;"><strong>সাবটোটাল:</strong></td>
                <td align="right" style="padding: 10px;">৳ {{ $order->total_amount }}</td>
            </tr>
            <tr>
                <td colspan="2" align="right" style="padding: 10px;"><strong>ডিসকাউন্ট:</strong></td>
                <td align="right" style="padding: 10px;">৳ {{ $order->coupon_discount ?? 0 }}</td>
            </tr>
            <tr>
                <td colspan="2" align="right" style="padding: 10px;"><strong>মোট বিল:</strong></td>
                <td align="right" style="padding: 10px;"><strong>৳ {{ $order->grand_total }}</strong></td>
            </tr>
        </tfoot>
    </table>
    <p style="margin-top: 50px; text-align: center;">আমাদের সাথে থাকার জন্য ধন্যবাদ!</p>
</div>
