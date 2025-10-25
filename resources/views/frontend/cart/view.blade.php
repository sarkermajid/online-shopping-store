@extends('frontend.master')

@section('title')
    My Cart
@endsection

@section('body')
    <!-- Shoping Cart Section Begin -->
    <section class="shoping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table class="">
                            <thead>
                                <tr>
                                    <th class="shoping__product">Products</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach ($carts as $cart)
                                    <tr>
                                        <td class="shoping__cart__item">
                                            <img src="{{ asset('admin/product-image/' . $cart->product->image) }}"
                                                alt="" width="80">
                                            <h5>{{ $cart->product->name }}</h5>
                                        </td>
                                        <td class="shoping__cart__price">
                                            @php
                                                $acceptedBargain = \App\Models\Bargain::where('user_id', auth()->id())
                                                    ->where('product_id', $cart->Product->id)
                                                    ->where('status', 1)
                                                    ->first();
                                            @endphp
                                            @if ($acceptedBargain)
                                                <span class="fw-bold">
                                                    {{ number_format($acceptedBargain->offered_price, 2) }}
                                                </span>
                                            @else
                                                <span class="fw-bold">
                                                    {{ number_format($cart->Product->discount_amount ?: $cart->Product->price, 2) }}
                                                </span>
                                            @endif
                                            {{ generalSettings('currency') }}
                                        </td>
                                        @if ($cart->product->qty >= $cart->product_qty)
                                            <td class="shoping__cart__quantity">
                                                <div class="quantity">
                                                    <div class="pro-qty">
                                                        <span class="dec qtybtn" data-id="{{ $cart->product_id }}">-</span>
                                                        <input type="text" value="{{ $cart->product_qty }}">
                                                        <span class="inc qtybtn" data-id="{{ $cart->product_id }}">+</span>
                                                    </div>
                                                </div>
                                            </td>
                                        @else
                                            <td><span class="text-danger">Out Of Stock</span></td>
                                        @endif
                                        <td class="shoping__cart__total">
                                            @php
                                                $acceptedBargainPrice = \App\Models\Bargain::where(
                                                    'user_id',
                                                    auth()->id(),
                                                )
                                                    ->where('product_id', $cart->product->id)
                                                    ->where('status', 1)
                                                    ->value('offered_price');
                                                $finalPrice =
                                                    $acceptedBargainPrice ??
                                                    ($cart->product->discount_amount ?? $cart->product->price);
                                                $totalPrice = $finalPrice * $cart->product_qty;
                                            @endphp

                                            {{ number_format($totalPrice, 2) }} {{ generalSettings('currency') }}
                                        </td>

                                        <td class="shoping__cart__item__close">
                                            <span class="icon_close remove_cart" data-id="{{ $cart->id }}"></span>
                                        </td>
                                    </tr>
                                    @php
                                        // Check if user has an accepted bargain for this product
                                        $acceptedBargain = \App\Models\Bargain::where('user_id', auth()->id())
                                            ->where('product_id', $cart->product->id)
                                            ->where('status', 1)
                                            ->first();

                                        if ($acceptedBargain) {
                                            $price = $acceptedBargain->offered_price;
                                        } else {
                                            $price = $cart->product->discount_amount ?: $cart->product->price;
                                        }

                                        $total += $price * $cart->product_qty;
                                    @endphp

                                    {{-- @php $total += $cart->product->discount_amount ? $cart->product->discount_amount * $cart->product_qty : $cart->product->price * $cart->product_qty @endphp --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="{{ route('shop') }}" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                    </div>
                </div>
                <div class="col-lg-6">
                </div>
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Cart Total</h5>
                        <ul>
                            <li>Total <span>{{ $total }} {{ generalSettings('currency') }}</span></li>
                        </ul>
                        <a href="{{ route('checkout') }}" class="primary-btn">PROCEED TO CHECKOUT</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shoping Cart Section End -->
@endsection
