<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #444;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        thead th {
            background-color: #ccc;
        }
        .text-right {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
        }
    </style>
</head>
<body>
    @if ($mailData['user_type'] == 'customer')
        <h1>Thanks for your order!</h1>
        <h2>Your Order ID is: #{{ $mailData['order']->id}}</h2>
    @else
        <h1>You Have received an Order!</h1>
        <h2>Order ID is: #{{ $mailData['order']->id}}</h2>
    @endif
    @php
        if(isset($_GET['session_id'])){
            $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
            $session_id = $_GET['session_id'];
            $stripeSession = $stripe->checkout->sessions->retrieve($session_id);

            // Display payment details
            echo "<p>Your payment has been successfully processed.</p>";
            echo "<p>Payment ID: " . $stripeSession->id . "</p>";
        }
    @endphp
    <h2 >Shipping Address</h2>
    <address>
        <strong>{{$mailData['order']->first_name .' '.$mailData['order']->last_name}}</strong><br>
        {{$mailData['order']->address}}<br>
        {{ucfirst(trans($mailData['order']->city)) .' '.ucfirst(trans($mailData['order']->state))}},{{' '.ucfirst(trans(getCountryName($mailData['order']->country_id)))}}<br>
        Phone: {{$mailData['order']->phone}}<br>
        Email: {{$mailData['order']->email}}<br>                                
        <strong>Shipped Date : {{!empty($mailData['order']->shipped_date) ? \Carbon\Carbon::parse($mailData['order']->shipped_date)->format("d M Y H:i:s") : 'N/A'}}</strong>
    </address>

    <h2>Products</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th width="100">Price</th>
                <th width="100">Qty</th>                                        
                <th width="100">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>₹{{number_format($item->price, 2)}}</td>                                        
                <td>{{$item->qty}}</td>
                <td>₹{{number_format($item->total, 2)}}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-right">Subtotal:</td>
                <td>₹{{number_format($mailData['order']->subtotal, 2)}}</td>
            </tr>
            @if($mailData['order']->discount > 0)
            <tr>
                <td colspan="3" class="text-right">Discount ({{$mailData['order']->coupone_code}}):</td>
                <td>₹{{number_format($mailData['order']->discount, 2)}}</td>
            </tr>                                
            @endif
            <tr>
                <td colspan="3" class="text-right">Shipping:</td>
                <td>₹{{number_format($mailData['order']->shipping, 2)}}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right">Grand Total:</td>
                <td>₹{{number_format($mailData['order']->grand_total, 2)}}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
