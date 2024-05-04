@extends('front.layout.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{route('front-home')}}">Shop</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form name="orderForm" id="orderForm" action="" method="post">
            {{-- @csrf --}}
            <div class="row">
                <div class="col-md-8">
                    <div class="sub-title">
                        <h2>Shipping Address</h2>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body checkout-form">
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ (!empty($customer_details->first_name)) ? $customer_details->first_name : ''}}">
                                        <p></p>
                                    </div>            
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ (!empty($customer_details->last_name)) ? $customer_details->last_name : ''}}">
                                        <p></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customer_details->email)) ? $customer_details->email : ''}}">
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <select name="country" id="country" class="form-control">
                                            <option value="">Select a Country</option>
                                            @if ($countries->isNotEmpty())
                                                @foreach ($countries as $country)
                                                    <option {{ (!empty($customer_details->country_id)  && $customer_details->country_id == $country->id ) ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{ (!empty($customer_details->address)) ? $customer_details->address : ''}}</textarea>
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="apartment" id="appartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)" value="{{ (!empty($customer_details->apartment)) ? $customer_details->apartment : ''}}">
                                    </div>            
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ (!empty($customer_details->city)) ? $customer_details->city : ''}}">
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{ (!empty($customer_details->state)) ? $customer_details->state : ''}}">
                                        <p></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ (!empty($customer_details->zip)) ? $customer_details->zip : ''}}">
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile No." value="{{ (!empty($customer_details->phone)) ? $customer_details->phone : ''}}">
                                        <p></p>
                                    </div>            
                                </div>
                                

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control"></textarea>
                                    </div>            
                                </div>

                            </div>
                        </div>
                    </div>    
                </div>
                <div class="col-md-4">
                    <div class="sub-title">
                        <h2>Order Summery</h3>
                    </div>                    
                    <div class="card cart-summery">
                        <div class="card-body">
                            @foreach (Cart::content() as $item)
                            <div class="d-flex justify-content-between pb-2">
                                <div class="h6">{{$item->name}} X {{$item->qty}}</div>
                                <div class="h6">₹{{$item->price * $item->qty}}</div>
                            </div>    
                            @endforeach
                            
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Subtotal</strong></div>
                                <div class="h6"><strong>₹{{Cart::subtotal()}}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Discount</strong></div>
                                <div class="h6"><strong id="discount_val">₹{{$discount}}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="h6"><strong>Shipping</strong></div>
                                <div class="h6"><strong id="shipping_amount">₹{{number_format($totalShippingCharge,2)}}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 summery-end">
                                <div class="h5"><strong>Total</strong></div>
                                <div class="h5"><strong id="grand_total">₹{{number_format($grandTotal,2)}}</strong></div>
                            </div>                            
                        </div>
                    </div>   
                    <div class="input-group apply-coupan mt-4">
                        <input type="text" placeholder="Coupon Code" class="form-control" name="coupon_code" id="coupon_code">
                        <button class="btn btn-dark" type="button" id="apply_coupon">Apply Coupon</button>
                    </div>
                    <span class="text-danger mt-5" id="error_coupon"></span>
                    <div id="discount-response-wrapper">
                        @if (Session::has('code'))
                        <div class="mt-4" id="discount-response">
                            <strong>{{Session::get('code')->code}}</strong>
                            <a class="btn btn-sm btn-danger" style="margin-left:100px" id="remove-discount"><i class="fa fa-times"></i></a>
                        </div>
                        @endif
                    </div>
                    <div class="card payment-form " > 
                        <h3 class="card-title h5 mb-3">Payment Method</h3>
                        <div>
                            <input checked type="radio" name="payment_method" id="payment_method_one" value="cod">
                            <label for="payment_method_one" class="form-check-label">COD</label>
                        </div>
                        <div>
                            <input type="radio" name="payment_method" id="payment_method_two" value="online_pay">
                            <label for="payment_method_two" class="form-check-label">Stripe</label>
                        </div>
                        <div class="card-body p-0 mt-3" style="display: none" id="payment_detail">
                            <div class="mb-3">
                                <label for="card_number" class="mb-2">Card Number</label>
                                <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">Expiry Date</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">CVV Code</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                                </div>
                            </div>
                        </div>    
                        <div class="pt-4">
                            {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                            <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                        </div>                            
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('js')
    <script>
        $("#country").on("change", function(){
            // alert($(this).val());
            $.ajax({
                url : '{{route("front.getCountryWiseCharge")}}',
                type : 'post',
                data : {country_id : $(this).val()},
                dataType : 'json',
                success : function(response){
                    if(response.status == true){
                        $("#shipping_amount").html('₹'+response.totalShippingCharge);
                        $("#grand_total").html('₹'+response.grandTotal);
                    }
                }
            })
        });

        $("#apply_coupon").on("click", function (){
            $.ajax({
                url : '{{route("front.applyDiscount")}}',
                type : 'post',
                data : {code : $("#coupon_code").val(), country_id:$("#country").val()},
                dataType : 'json',
                success : function(response){
                    if(response.status == true){
                        $("#discount_val").html(response.discount);
                        $("#shipping_amount").html('₹'+response.totalShippingCharge);
                        $("#grand_total").html('₹'+response.grandTotal);
                        $("#discount-response-wrapper").html(response.discount_html);
                        $("#error_coupon").html('');
                    }else{
                        $("#error_coupon").html(response.message);
                    }
                }
            })
        })

        $('body').on("click", "#remove-discount", function(){
            $.ajax({
                url : '{{route("front.removeDiscount")}}',
                type : 'post',
                data : {country_id:$("#country").val()},
                dataType : 'json',
                success : function(response){
                    if(response.status == true){
                        $("#discount_val").html(response.discount);
                        $("#shipping_amount").html('₹'+response.totalShippingCharge);
                        $("#grand_total").html('₹'+response.grandTotal);
                        $("#discount-response").html('');
                        $("#coupon_code").val('');
                    }
                }
            })
        })

        $("#payment_method_two").on("click", function(){
            // $("#payment_detail").show();
            
        });
        $("#payment_method_one").on("click", function(){
            $("#payment_detail").hide();
        });

        $("#orderForm").on("submit", function(e){
            e.preventDefault();
            // alert('form submited');
            $.ajax({
                url : '{{route("front.processCheckout")}}',
                type : 'post',
                data : $(this).serializeArray(),
                dataType : 'json',
                success : function(response){
                    var errors = response.errors;
                    if(response.status == false){
                        if(errors.first_name){
                            $("#first_name").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.first_name);
                        }else{
                            $("#first_name").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        }

                        if(errors.last_name){
                            $("#last_name").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.last_name);
                        }else{
                            $("#last_name").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        }

                        if(errors.email){
                            $("#email").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.email);
                        }else{
                            $("#email").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        }

                        if(errors.country){
                            $("#country").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.country);
                        }else{
                            $("#country").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        }

                        if(errors.address){
                            $("#address").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.address);
                        }else{
                            $("#address").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        }

                        if(errors.city){
                            $("#city").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.city);
                        }else{
                            $("#city").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        }

                        if(errors.state){
                            $("#state").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.state);
                        }else{
                            $("#state").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        }

                        if(errors.zip){
                            $("#zip").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.zip);
                        }else{
                            $("#zip").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        }

                        if(errors.mobile){
                            $("#mobile").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.mobile);
                        }else{
                            $("#mobile").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        }
                    }else{
                        console.log(response);
                        var stripeCheckoutUrl = response.url;
                        if(stripeCheckoutUrl){
                            window.location.href = stripeCheckoutUrl;
                        }else{
                            window.location.href = "{{url('/thanks/')}}/"+response.orderId;
                        }
                    }
                    
                },
                error : function(jQXHR, exception){
                    console.log('something went wrong');
                }
            });
        });
    </script>
@endsection