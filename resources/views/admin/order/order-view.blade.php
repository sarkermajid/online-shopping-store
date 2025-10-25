@extends('admin.master')

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center" style="background-color: #2A3042">
                        <h3 class="text-center profile-heading-btn text-white text-uppercase text-center">Order Details</h3>
                        <a href="{{ route('order.invoice', ['id' => $order->id]) }}" class="btn btn-outline-success">Generate
                            Invoice</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <h4 class="text-center text-uppercase">Shipping Address</h4>
                                    <tbody>
                                        <tr>
                                            <td class="payment-title"><strong>Name :</strong></td>
                                            <td class="text-style">{{ $order->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="payment-title"><strong>Phone :</strong></td>
                                            <td class="text-style">{{ $order->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td class="payment-title"><strong>Address :</strong></td>
                                            <td class="text-style">{{ $order->address }}</td>
                                        </tr>
                                        <tr>
                                            <td class="payment-title"><strong>City :</strong></td>
                                            <td class="text-style">{{ $order->city }}</td>
                                        </tr>
                                        <tr>
                                            <td class="payment-title"><strong>Zip Code :</strong></td>
                                            <td class="text-style">{{ $order->zip_code }}</td>
                                        </tr>
                                        <tr>
                                            <td class="payment-title"><strong>Date :</strong></td>
                                            <td class="text-style">{{ $order->created_at->format('m-d-Y') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <h4 class="text-center text-uppercase">Product Details</h4>
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderItem as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ $item->product_qty }}</td>
                                                @php $total = 0; @endphp
                                                @php
                                                    $acceptedBargainPrice = \App\Models\Bargain::where(
                                                        'user_id',
                                                        $user->id,
                                                    )
                                                        ->where('product_id', $item->Product->id)
                                                        ->where('status', 1)
                                                        ->value('offered_price');

                                                    $finalPrice =
                                                        $acceptedBargainPrice ??
                                                        ($item->discount_amount ?? $item->price);

                                                    $lineTotal = $finalPrice * $item->product_qty;
                                                    $total += $lineTotal;
                                                @endphp

                                                <td>{{ number_format($finalPrice, 2) }} {{ generalSettings('currency') }}
                                                </td>
                                                <td>{{ number_format($lineTotal, 2) }} {{ generalSettings('currency') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <h5 class="px-2">Total Price: <span class="float-right">{{  number_format($order->total_price,2) }}
                                        {{ generalSettings('currency') }}</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
@endsection
