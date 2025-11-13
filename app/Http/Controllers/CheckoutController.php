<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutStoreRequest;
use App\Models\ApplyPromoCode;
use App\Models\Bargain;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $carts = Cart::where('user_id', Auth::id())->get();
        $cartTotalBalance = 0;
        foreach ($carts as $cart) {
            if (! Product::where('id', $cart->product_id)->where('qty', '>=', $cart->product_qty)->exists()) {
                $removeCart = Cart::where('user_id', Auth::id())->where('product_id', $cart->product_id)->first();
                $removeCart->delete();
            }
        }
        $carts = Cart::where('user_id', Auth::id())->get();

        return view('frontend.checkout.index', compact('carts', 'cartTotalBalance'));
    }

    public function placeOrder(CheckoutStoreRequest $request)
    {
        $carts = Cart::where('user_id', auth()->user()->id)->get();

        if ($carts->count() < 1) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        if ($request->payment_method === 'cod') {
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->zip_code = $request->zip_code;
            $order->total_price = $request->total_price;
            $order->tracking_number = rand(100000, 500000);
            $order->payment_method = 'Cash on Delivery';
            $order->save();

            foreach ($carts as $cart) {
                // create orderitem table
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cart->product_id;
                $orderItem->product_qty = $cart->product_qty;
                $orderItem->price = $cart->product->discount_amount ? $cart->product->discount_amount : $cart->product->price;
                $orderItem->save();

                // product quantity decrement after oder place
                $product = Product::where('id', $orderItem->product_id)->first();
                $product->qty = $product->qty - $orderItem->product_qty;
                $product->update();
            }

            // user data update for easily order next time
            if (auth()->user()->address == null || auth()->user()->city == null || auth()->user()->zip_close == null) {
                $user = User::where('id', auth()->user()->id)->first();
                $user->address = $request->address;
                $user->city = $request->city;
                $user->zip_code = $request->zip_code;
                $user->save();
            }
            // after order carts will be removed
            $carts = Cart::where('user_id', auth()->user()->id)->get();
            Cart::destroy($carts);

            return redirect()->back()->with('message', 'Order Place successfully');
        } elseif ($request->payment_method === 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $line_items = [];
            foreach ($carts as $cart) {
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'bdt',
                        'product_data' => [
                            'name' => $cart->product->name,
                        ],
                        'unit_amount' => ($cart->product->discount_amount ?: $cart->product->price) * 100,
                    ],
                    'quantity' => $cart->product_qty,
                ];
            }

            $checkout_session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'customer_email' => auth()->user()->email,
            ]);

            // Save pending order
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->zip_code = $request->zip_code;
            $order->total_price = $request->total_price;
            $order->tracking_number = rand(100000, 500000);
            $order->payment_method = 'stripe';
            $order->status = 0;
            $order->transaction_id = $checkout_session->id;
            $order->save();
            foreach ($carts as $cart) {
                // create orderitem table
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cart->product_id;
                $orderItem->product_qty = $cart->product_qty;
                $orderItem->price = $cart->product->discount_amount ? $cart->product->discount_amount : $cart->product->price;
                $orderItem->save();
            }
            session(['order_id' => $order->id]);

            return redirect($checkout_session->url);
        } else {
            return back()->with('error', 'Please select a payment method.');
        }
    }

    public function stripeSuccess(Request $request)
    {
        $order = Order::find(session('order_id'));
        if ($order) {
            $order->status = 1;
            $order->save();

            // Reduce product quantity
            $carts = Cart::where('user_id', auth()->user()->id)->get();
            foreach ($carts as $cart) {
                $product = Product::find($cart->product_id);
                $product->qty -= $cart->product_qty;
                $product->save();
            }

            Cart::where('user_id', auth()->user()->id)->delete();
        }

        return redirect()->route('home')->with('message', 'Payment successful & Order placed!');
    }

    public function stripeCancel()
    {
        return redirect()->route('checkout')->with('error', 'Payment canceled!');
    }


    public function applyPromoCode(Request $request)
    {
        $carts = Cart::where('user_id', Auth::id())->get();
        $cartTotalBalance = 0;

        if ($carts->count() > 0) {
            $cartTotalBalance = 0;

            foreach ($carts as $cart) {
                $bargain = Bargain::where('user_id', $cart->user_id)
                    ->where('product_id', $cart->product_id)
                    ->where('status', 1)
                    ->latest()
                    ->first();
                if ($bargain) {
                    $price = $bargain->offered_price;
                } elseif ($cart->product->discount_amount) {
                    $price = $cart->product->discount_amount;
                } else {
                    $price = $cart->product->price;
                }
                $cartTotalBalance += $price * $cart->product_qty;
            }
            // dd($cartTotalBalance);
            $promoCode = PromoCode::where('code', $request->promo_code)->first();
            $applyPromoCode = ApplyPromoCode::where('user_id', auth()->user()->id)->first();
            if ($promoCode) {
                if (! $applyPromoCode) {
                    $currentDate = Carbon::now();
                    $expiryDate = Carbon::createFromFormat('m/d/Y', $promoCode->expire_date);
                    if ($currentDate < $expiryDate) {
                        if ($promoCode->limit > 0) {
                            if ($promoCode->type == 1) {
                                $cartTotalBalance -= ($promoCode->discount / 100) * $cartTotalBalance;
                            } else {
                                $cartTotalBalance -= $promoCode->discount;
                            }
                            $promoCode->used = $promoCode->used + 1;
                            if ($promoCode->limit != 0) {
                                $promoCode->limit = $promoCode->limit - 1;
                            }
                            $promoCode->save();
                            $applyPromoCode = new ApplyPromoCode();
                            $applyPromoCode->promo_code_id = $promoCode->id;
                            $applyPromoCode->user_id = auth()->user()->id;
                            $applyPromoCode->save();

                            return view('frontend.checkout.index', compact('cartTotalBalance', 'carts'))->with('message', 'Promo Code applied successfully');
                        } else {
                            $promoCode->status = 0;
                            $promoCode->save();

                            return redirect()->back()->with('error', 'Promo code limit is out of range');
                        }
                    } else {
                        $promoCode->status = 0;
                        $promoCode->save();

                        return redirect()->back()->with('error', 'Promo code expired');
                    }
                } else {
                    return redirect()->back()->with('error', 'You are already used promo code');
                }
            } else {
                return redirect()->back()->with('error', 'Promo code not found');
            }
        } else {
            return redirect()->route('home');
        }
    }
}
