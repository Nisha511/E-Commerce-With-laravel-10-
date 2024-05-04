<?php

namespace App\Http\Controllers;

use App\Mail\OrderEmail;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\payment;
use App\Models\Product;
use App\Models\ShippingCharges;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Redirect;
class CartController extends Controller
{
    //
    public function addToCart(Request $request){
        $product = Product::with('product_images')->find($request->id);
        // dd($product);
        if($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found.'
            ]);
        }
        if (Cart::count() > 0) {;
            $cartContent = Cart::content();
            $productAlreadyExits = false;
            foreach($cartContent as $item){
                if($item->id == $product->id){
                    $productAlreadyExits = true;
                }
            }   
            if($productAlreadyExits == false){
                $productImage = (!empty($product->product_images) && $product->product_images->isNotEmpty())
                                ? $product->product_images->first()
                                : '';
                Cart::add($product->id, $product->name, 1, $product->price, 
                ['productImage' => $productImage]);
                $status = true;
                $message = $product->name.' added in cart';
            }else{
                $status = false;
                $message = $product->name.' already added in cart';
            }
        } else {
            // Cart is empty, adding new product to cart
            $productImage = (!empty($product->product_images) && $product->product_images->isNotEmpty())
                                ? $product->product_images->first()
                                : '';
            Cart::add($product->id, $product->name, 1, $product->price, 
            ['productImage' =>$productImage]);
            $status = true;
            $message = $product->name.' added in cart';
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    public function cart(Request $request){
        // Cart::remove('dbc88af9de6f06630d097175dbe72508');
        // Remove each item in the cart
        // $cartProducts = Cart::content();
        // foreach ($cartProducts as $cartProduct) {
        //     Cart::remove($cartProduct->rowId);
        // }
        // Cart::destroy();
        // dd($cartProducts);
        // request()->session()->flush();

        $updatedCartProducts = Cart::content();
        // dd($updatedCartProducts);
        // foreach($updatedCartProducts as $updatedCartProduct){
        //     $find_product = Product::find($updatedCartProduct);
        //     // dd($find_product);
        //     $data['find_product'] = $find_product;    
        // }
       
        $data['cartProducts'] = $updatedCartProducts;

        return view('front.cart',$data);
    }
    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;
        $cartInfo = Cart::get($rowId);
        $product = Product::find($cartInfo->id);
        // dd($product);
        if($product->track_qty == 'Yes'){
            if($qty <= $product->qty){
                // echo "inside if";
                $cart = Cart::update($rowId,$qty);
                $message = 'Cart Updated Successfully.';
                $status = true;
                return response()->json([
                    'status' => $status,
                    'message' => $message,
                    'cart_id' => $cart->id,
                    'cart_qty' => $cart->qty,
                    'cart_price' => $cart->price,
                    'qty_in_stock' => $product->qty,
                ]);
            }else{
                // echo "inside else";
                $message = 'Request qty('.$qty.') not available in stock';
                $status = false;
                return response()->json([
                    'status' => $status,
                    'message' => $message,
                ]);
            }
        }else{
            $cart = Cart::update($rowId,$qty);
            $message = 'Cart Updated Successfully.';
            $status = true;
            return response()->json([
                'status' => $status,
                'message' => $message,
                'cart_id' => $cart->id,
                'cart_qty' => $cart->qty,
                'cart_price' => $cart->price,
                // 'qty_in_stock' => $product->qty,
            ]);
        }
        // echo "outside niether inif nor else";exit;
    }
    public function getCartSummary()
    {
        $cartSummary = [
            'total' => Cart::subtotal(), 
        ];
        return response()->json($cartSummary);
    }
    public function deleteCart(Request $request){
        $rowId = $request->rowId;
        $cart = Cart::get($rowId);
        if($cart == null){
            return response()->json([
                'status' => true,
                'message' => 'Cart Not Found.'
            ]);
        }else{
            $cart = Cart::remove($rowId);
            $message = 'Cart Deleted Successfully.';
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }
    }
    public function checkout(){
        $discount = 0;
        if(Cart::count() == 0){
            return redirect()->route('front.cart');
        }
        if(Auth::check() == false){
            if(!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }
        $customer_details = CustomerAddress::where('user_id',Auth::user()->id)->first();
        session()->forget('url.intended');
        $countries = Country::orderBy('name','ASC')->get();
        $subTotal = Cart::subtotal(2,'.','');
        if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == 'percent'){
                $discount = ($code->discount_amount/100) * $subTotal;
            }else{
                $discount = $code->discount_amount;
            }
        }
        
        $shippingCharge = ShippingCharges::where('country_id',$customer_details->country_id)->first();
        $shippingChargeAmount = $shippingCharge->amount;
        $totalShippingCharge = 0;
        $totalQty = 0;
        $grandTotal = 0;
        foreach(Cart::content() as $item){
            $totalQty += $item->qty;
        }
        $totalShippingCharge = $totalQty*$shippingChargeAmount;
        $grandTotal = ($subTotal - $discount) + $totalShippingCharge;        
        
        return view('front.checkout',[
            'countries' => $countries,
            'customer_details' => $customer_details,
            'discount' => $discount,
            'totalShippingCharge' => $totalShippingCharge,
            'grandTotal' => $grandTotal
        ]);
    }
    public function processCheckout(Request $request){
        // echo "dgrfdg";exit;
        $validator = Validator::make($request->all(),[
            "first_name" => "required",
            "last_name" => "required",
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' =>'required',
            'mobile' => 'required'
        ]);
        
        if($validator->passes()){
            $user = Auth::user();
            // echo "<PRE>";print_r($user);exit;
            $customer_address = CustomerAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->mobile,
                    'country_id' => $request->country,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                ]
            );

            if($request->payment_method == 'cod'){
                $coupone_code = '';
                $coupon_code_id = 0;
                $shipping = 0;
                $discount = 0;
                $totalQty = 0;
                $grandTotal = 0;
                $subtotal = Cart::subtotal(2,'.','');

                if(session()->has('code')){
                    $code = session()->get('code');
                    if($code->type == 'percent'){
                        $discount = ($code->discount_amount/100) * $subtotal;
                    }else{
                        $discount = $code->discount_amount;
                    }
                    $coupone_code = $code->code;
                    $coupon_code_id = $code->id;
                }

                $shippingCharge = ShippingCharges::where('country_id',$request->country)->first();
                foreach(Cart::content() as $item){
                    $totalQty += $item->qty;
                }
                if($shippingCharge !== null){
                    $shippingChargeAmount = $shippingCharge->amount;
                    $shipping = $totalQty*$shippingChargeAmount;
                    $grandTotal = ($subtotal - $discount) + $shipping;
                }else{
                    $shippingCharge = ShippingCharges::where('country_id','rest_of_world')->first();
                    $shippingChargeAmount = $shippingCharge->amount;
                    $shipping = $totalQty*$shippingChargeAmount;
                    $grandTotal = ($subtotal - $discount) + $shipping;
                }

                $order = new Order();
                $order->user_id = $user->id;
                $order->subtotal = $subtotal;
                $order->shipping = $shipping;
                $order->discount = $discount;
                if(isset($coupone_code)){
                    $order->coupone_code = $coupone_code;
                }
                if($coupon_code_id){
                    $order->coupon_code_id = $coupon_code_id;
                }
                $order->grand_total = $grandTotal;
                $order->payment_status = 'not paid';
                $order->status = 'pending';
                $order->first_name = $request->first_name;
                $order->last_name = $request->last_name;
                $order->email = $request->email;
                $order->phone = $request->mobile;
                $order->country_id = $request->country;
                $order->address = $request->address;
                $order->apartment = $request->apartment;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zip = $request->zip;
                $order->notes = $request->order_notes;
                $order->save();

                foreach(Cart::content() as $item){
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id =  $item->id;
                    $orderItem->name = $item->name;
                    $orderItem->qty = $item->qty;
                    $orderItem->price = $item->price;
                    $orderItem->total = $item->price * $item->qty;
                    $orderItem->save();
                }

                orderEmail($order->id,'customer');
                $request->session()->flash('success','Your order place successfully.');
                Cart::destroy();
                session()->forget('code');
                return response()->json([
                    'status' => true,
                    'message' => 'Your order place successfully.',
                    'orderId' => $order->id,
                ]);
            }else{
                $coupone_code = '';
                $coupon_code_id = 0;
                $shipping = 0;
                $discount = 0;
                $totalQty = 0;
                $grandTotal = 0;
                $subtotal = Cart::subtotal(2,'.','');

                if(session()->has('code')){
                    $code = session()->get('code');
                    if($code->type == 'percent'){
                        $discount = ($code->discount_amount/100) * $subtotal;
                    }else{
                        $discount = $code->discount_amount;
                    }
                    $coupone_code = $code->code;
                    $coupon_code_id = $code->id;
                }

                $shippingCharge = ShippingCharges::where('country_id',$request->country)->first();
                foreach(Cart::content() as $item){
                    $totalQty += $item->qty;
                }
                if($shippingCharge !== null){
                    $shippingChargeAmount = $shippingCharge->amount;
                    $shipping = $totalQty*$shippingChargeAmount;
                    $grandTotal = ($subtotal - $discount) + $shipping;
                }else{
                    $shippingCharge = ShippingCharges::where('country_id','rest_of_world')->first();
                    $shippingChargeAmount = $shippingCharge->amount;
                    $shipping = $totalQty*$shippingChargeAmount;
                    $grandTotal = ($subtotal - $discount) + $shipping;
                }

                $order = new Order();
                $order->user_id = $user->id;
                $order->subtotal = $subtotal;
                $order->shipping = $shipping;
                $order->discount = $discount;
                if(isset($coupone_code)){
                    $order->coupone_code = $coupone_code;
                }
                if($coupon_code_id){
                    $order->coupon_code_id = $coupon_code_id;
                }
                $order->grand_total = $grandTotal;
                $order->payment_status = 'paid';
                $order->status = 'pending';
                $order->first_name = $request->first_name;
                $order->last_name = $request->last_name;
                $order->email = $request->email;
                $order->phone = $request->mobile;
                $order->country_id = $request->country;
                $order->address = $request->address;
                $order->apartment = $request->apartment;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zip = $request->zip;
                $order->notes = $request->order_notes;
                $order->save();

                foreach(Cart::content() as $item){
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id =  $item->id;
                    $orderItem->name = $item->name;
                    $orderItem->qty = $item->qty;
                    $orderItem->price = $item->price;
                    $orderItem->total = $item->price * $item->qty;
                    $orderItem->save();
                }

                // orderEmail($order->id,'customer');
                // $request->session()->flash('success','Your order place successfully.');
                
                // return response()->json([
                //     'status' => true,
                //     'message' => 'Your order place successfully.',
                //     'orderId' => $order->id,
                // ]);
                $stripeController = new StripeController();
                $payment_data = $stripeController->processPayment($request,$order->id);
                if ($payment_data->getStatusCode() === 200) {
                    return $payment_data;
                } else {
                    return "error while redirecting";
                }

            }

        }else{
            return response()->json([
                'message' => 'Please fix issued',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function success(Request $request){
        if(isset($request->session_id)){
            $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
            $response = $stripe->checkout->sessions->retrieve($request->session_id);
            dd($response);
        }else{
            return "Thank you for order You have just completed your payment. The seeler will reach out to you as soon as possible.";
        }
    }
    public function cancel(){
        return "cancel";
    }
    public function thankyou(Request $request, $id) {
        if ($request->has('session_id')) {
            $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
            $session_id = $request->session_id;
            $stripeSession = $stripe->checkout->sessions->retrieve($session_id);
            // echo "<PRE>";print_r($stripeSession);exit;
                $quantity = session()->has('quantity') ? session()->get('quantity') : 0;
                $amount = session()->has('price') ? session()->get('price') : 0;
                $currency = $stripeSession->currency;
                $customer_name = $stripeSession->customer_details->name;
                $customer_email = $stripeSession->customer_details->email;
                $payment_status = $stripeSession->payment_status;
                if($amount > 0 ){
                    $payment = new payment();
                    $payment->payment_id = $stripeSession->id;
                    $payment->quantity = $quantity;
                    $payment->amount = $amount;
                    $payment->currency = $currency;
                    $payment->customer_name = $customer_name;
                    $payment->customer_email = $customer_email;
                    $payment->paymnet_status = $payment_status;
                    $payment->payment_method = "Stripe";
                    $payment->save();
                    Cart::destroy();
                    orderEmail($id,'customer');
                    session()->forget('code');
                    $request->session()->flash('success','Your order place successfully and payment also completed.');
                }
                session()->forget('price');
                session()->forget('quantity');
                return view('front.thanks', ['id' => $id]);
            } else {
                return view('front.thanks', ['id' => $id]);
            }
    }
    
    
    public function getCountryWiseCharge(Request $request){
        $subTotal = Cart::subtotal(2,'.','');
        $discount = 0;
        $discount_html = '';
        if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == 'percent'){
                $discount = ($code->discount_amount/100) * $subTotal;
            }else{
                $discount = $code->discount_amount;
            }
            $discount_html='<div class="mt-4" id="discount-response">
                                <strong>'.session()->get('code')->code.'</strong>
                                <a class="btn btn-sm btn-danger" style="margin-left:100px" id="remove-discount"><i class="fa fa-times"></i></a>
                            </div>';
        }
        if($request->country_id > 0){
            $shippingCharge = ShippingCharges::where('country_id',$request->country_id)->first();
            
            $totalShippingCharge = 0;
            $totalQty = 0;
            $grandTotal = 0;
            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }
            if($shippingCharge !== null){
                $shippingChargeAmount = $shippingCharge->amount;
                $totalShippingCharge = $totalQty*$shippingChargeAmount;
                $grandTotal = ($subTotal - $discount) + $totalShippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discount_html' => $discount_html,
                    'totalShippingCharge' => number_format($totalShippingCharge,2)
                ]);
            }else{
                $shippingCharge = ShippingCharges::where('country_id','rest_of_world')->first();
                $shippingChargeAmount = $shippingCharge->amount;
                $totalShippingCharge = $totalQty*$shippingChargeAmount;
                $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
                
                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discount_html' => $discount_html,
                    'totalShippingCharge' => number_format($totalShippingCharge,2)
                ]);
            }
        }else{
            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal - $discount),2),
                'discount' => number_format($discount,2),
                'discount_html' => $discount_html,
                'totalShippingCharge' => number_format(0,2)
            ]); 
        }
    }
    public function applyDiscount(Request $request){
        $code = DiscountCoupon::where('code',$request->code)->first();
        if($code == null){
            return response()->json([
                'status' => false,
                'message' => 'Invalid Discount Coupon'
            ]);
        }

        $now = Carbon::now();
        if($code->starts_at != ""){
            $startDate = Carbon::createFromFormat("Y-m-d H:i:s",$code->starts_at);
            if($now->lt($startDate)){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount Coupon',
                ]);
            }
        }

        if($code->expires_at != ""){
            $endDate = Carbon::createFromFormat("Y-m-d H:i:s",$code->expires_at);
            if($now->gt($endDate)){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount Coupon',
                ]);
            }
        }
        if($code->max_uses > 0){
            $counponUsed = Order::where('coupon_code_id',$code->id)->count();
            if($counponUsed >= $code->max_uses){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount Coupon',
                ]);
            }
        }

        if($code->max_uses_user > 0){
            $couponUsedByUser = Order::where(['coupon_code_id'=>$code->id,'user_id'=>Auth::user()->id])->count();
            if($couponUsedByUser >= $code->max_uses_user){
                return response()->json([
                    'status' => false,
                    'message' => 'You Already apply this coupon',
                ]);
            }
        }
        $subTotal = Cart::subtotal(2,'.','');
        if($code->min_amount > 0){
            if($subTotal < $code->min_amount){
                return response()->json([
                    'status' => false,
                    'message' => 'If you want to use this coupon, you need to shop at least minimum amount of ₹'.$code->min_amount.' .',
                ]);
            }
        }
        session()->put('code',$code);
        return $this->getCountryWiseCharge($request);
    }
    public function removeDiscount(Request $request){
        session()->forget('code');
        return $this->getCountryWiseCharge($request);
    }

}
