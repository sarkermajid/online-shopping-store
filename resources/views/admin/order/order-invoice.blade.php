<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .invoice {
            width: 80%;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .bill-to {
            margin-top: 20px;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items th,
        .items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .total {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="invoice">
        <div class="header">
            <h2>Order Invoice</h2>
        </div>

        <div class="bill-to">
            <h3>Bill To:</h3>
            <p>Name: {{ $order->name }}</p>
            <p>Phone: {{ $order->phone }}</p>
            <p>Address: {{ $order->address }}</p>
            <p>City: {{ $order->city }}</p>
            <p>Zip Code: {{ $order->zip_code }}</p>
            <p>Payment Method: {{ $order->payment_method }}</p>
            <p>Status: {{ $order->status == 2 ? 'Completed' : 'Pending' }}</p>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItem as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product_qty }}</td>
                        @php
                            $acceptedBargainPrice = \App\Models\Bargain::where('user_id', $user->id)
                                ->where('product_id', $item->Product->id)
                                ->where('status', 1)
                                ->value('offered_price');

                            $finalPrice = $acceptedBargainPrice ?? ($item->discount_amount ?? $item->price);

                            $lineTotal = $finalPrice * $item->product_qty;
                        @endphp
                        <td>{{ number_format($finalPrice, 2) }} {{ generalSettings('currency') }}</td>
                        <td>{{ number_format($lineTotal, 2) }} {{ generalSettings('currency') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p><strong>Total:</strong> {{ $order->total_price }} {{ generalSettings('currency') }}</p>
        </div>
    </div>

</body>

</html>
