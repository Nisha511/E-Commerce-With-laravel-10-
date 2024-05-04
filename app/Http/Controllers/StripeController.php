<?php

namespace App\Http\Controllers;

use App\Models\ShippingCharges;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    //
    public function processPayment(Request $request, $orderId)
    {
        Stripe::setApiKey(config('stripe.stripe_sk'));
        $productItems = [];
        $subtotal = Cart::subtotal(2,'.','');
        $shipping = 0;
        $discount = 0;
        $shippingCharge = ShippingCharges::where('country_id', $request->country)->first();
        if ($shippingCharge !== null) {
            $shippingChargeAmount = $shippingCharge->amount;
            $totalQty = 0;
            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }
            $shipping = $shippingChargeAmount * $totalQty;
        }

        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subtotal;
            } else {
                $discount = $code->discount_amount;
            }
        }
        
        foreach (Cart::content() as $item) {
            $total = round($item->price * 100);

            $productItems[] = [
                'price_data' => [
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'currency' => 'inr',
                    'unit_amount' => $total,
                ],
                'quantity' => $item->qty,
            ];
        }
        $grandTotal = $subtotal + $shipping - $discount;
        $productItems[] = [
            'price_data' => [
                'currency' => 'inr',
                'unit_amount' => $grandTotal,
                'product_data' => [
                    'name' => 'Shipping Charge',
                ],
            ],
            'quantity' => $totalQty, 
        ];
        
        // echo "<PRE>";print_r($productItems);exit;
        $checkoutSession = Session::create([
            'line_items' => $productItems,
            'mode' => 'payment',
            'allow_promotion_codes' => true,
            'metadata' => [
                'user_id' => Auth::user()->id,
            ],
            'customer_email' => Auth::user()->email,
            'billing_address_collection' => 'required', 
            'shipping_address_collection' => [
                'allowed_countries' => ['IN'], 
            ],
            'success_url' => url()->route('front.thanks', ['order_id' => $orderId]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('front.checkout'),
        ]);
        session()->put('quantity',$totalQty);
        session()->put('price',$grandTotal);
        // echo "<PRE>";print_r($checkoutSession);exit;
        return response()->json([
            'url' => $checkoutSession->url,
            'session_id' =>$checkoutSession->id
        ]);
        // return redirect()->to($checkoutSession->url);
    }
}
