@extends('frontend.master')

@section('title')
    Checkout
@endsection

@section('body')
    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h6><span class="icon_tag_alt"></span>
                        <span for="promo_question">Do you have a Promo Code?</span>
                        <input class="promo_question" type="checkbox" name="promo_question" value="1"
                            onchange="valueChanged()" />
                        <span class="item-text">Yes</span>
                    </h6>
                </div>
                <div class="col-lg-6 mx-auto">
                    <div class="shoping__discount" style="display: none">
                        <h5>Apply Promo Code</h5>
                        <form id="applyPromoForm" action="{{ route('applyPromoCode') }}" method="get">
                            @csrf
                            <input type="text" class="promo-code" name="promo_code" placeholder="Enter your Promo Code">
                            <button type="submit" class="site-btn apply-promo">APPLY PROMO</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
            </div>
            <div class="checkout__form">
                <h4>Billing Details</h4>
                <form action="{{ route('placeOrder') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="checkout__input">
                                        <p>Name<span>*</span></p>
                                        @error('name')
                                            <h6 class="modal-header justify-content-start"
                                                style="font-weight: 800; color: #ffffff; background-color: red; padding: 10px;  padding-bottom: 10px; font-size: 12px; max-width: 100%; border-radius: 5px;">
                                                {{ $message }}</h6>
                                        @enderror
                                        <input type="text" name="name" required value="{{ auth()->user()->name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="checkout__input">
                                        <p>Phone<span>*</span></p>
                                        @error('phone')
                                            <h6 class="modal-header justify-content-start"
                                                style="font-weight: 800; color: #ffffff; background-color: red; padding: 10px;  padding-bottom: 10px; font-size: 12px; max-width: 100%; border-radius: 5px;">
                                                {{ $message }}</h6>
                                        @enderror
                                        <input type="number" name="phone" required
                                            value="{{ auth()->user()->phone ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__input">
                                <p>Address<span>*</span></p>
                                @error('address')
                                    <h6 class="modal-header justify-content-start"
                                        style="font-weight: 800; color: #ffffff; background-color: red; padding: 10px;  padding-bottom: 10px; font-size: 12px; max-width: 100%; border-radius: 5px;">
                                        {{ $message }}</h6>
                                @enderror
                                <input type="text" name="address" required value="{{ auth()->user()->address ?? '' }}"
                                    class="checkout__input__add">
                            </div>
                            <div class="checkout__input">
                                <p>City/Town<span>*</span></p>
                                @error('city')
                                    <h6 class="modal-header justify-content-start"
                                        style="font-weight: 800; color: #ffffff; background-color: red; padding: 10px;  padding-bottom: 10px; font-size: 12px; max-width: 100%; border-radius: 5px;">
                                        {{ $message }}</h6>
                                @enderror
                                <input type="text" name="city" required value="{{ auth()->user()->city ?? '' }}">
                            </div>
                            <div class="checkout__input">
                                <p>Postcode / ZIP<span>*</span></p>
                                @error('zip_code')
                                    <h6 class="modal-header justify-content-start"
                                        style="font-weight: 800; color: #ffffff; background-color: red; padding: 10px;  padding-bottom: 10px; font-size: 12px; max-width: 100%; border-radius: 5px;">
                                        {{ $message }}</h6>
                                @enderror
                                <input type="number" name="zip_code" required
                                    value="{{ auth()->user()->zip_code ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4>Your Order</h4>
                                <div class="checkout__order__products">Products <span>Total</span></div>
                                <ul>
                                    @php $total = 0; @endphp
                                    @foreach ($carts as $cart)
                                        @php
                                            $acceptedBargainPrice = \App\Models\Bargain::where('user_id', auth()->id())
                                                ->where('product_id', $cart->product->id)
                                                ->where('status', 1)
                                                ->value('offered_price');

                                            $finalPrice =
                                                $acceptedBargainPrice ??
                                                ($cart->product->discount_amount ?? $cart->product->price);

                                            $lineTotal = $finalPrice * $cart->product_qty;

                                            $total += $lineTotal;
                                        @endphp

                                        <li>
                                            {{ Str::substr($cart->product->name, 0, 20) }}
                                            <span>{{ number_format($lineTotal, 2) }}
                                                {{ generalSettings('currency') }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <input type="hidden" name="total_price"
                                    value="{{ $cartTotalBalance != 0 ? $cartTotalBalance : $total }}">
                                <hr>

                                <div class="checkout__order__total">
                                    Total
                                    <span>{{ $cartTotalBalance != 0 ? number_format($cartTotalBalance, 2) : number_format($total, 2) }}
                                        {{ generalSettings('currency') }}</span>
                                </div>
                                {{-- <div class="checkout__input__checkbox">
                                        <label for="payment">
                                            Cash On Delivery
                                            <input type="checkbox" id="payment">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="paypal">
                                            Bkash
                                            <input type="checkbox" id="paypal">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="paypal">
                                            Nagad
                                            <input type="checkbox" id="paypal">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div> --}}
                                <button type="submit" class="site-btn">PLACE ORDER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
@endsection
<script>
    // do you have promo js
    function valueChanged() {
        if ($('.promo_question').is(":checked"))
            $(".shoping__discount").show();
        else
            $(".shoping__discount").hide();
    };
</script>
