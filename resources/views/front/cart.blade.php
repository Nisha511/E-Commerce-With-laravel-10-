@extends('front.layout.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{route('front-home')}}">Shop</a></li>
                <li class="breadcrumb-item">Cart</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-9 pt-4">
    <div class="container">
        <div class="row">
            @if (Cart::count() > 0)
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table" id="cart">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartProducts as $cartProduct)
                            <tr id="cartrow_{{$cartProduct->id}}">
                                <td>
                                    <div class="d-flex align-items-center ">
                                        {{-- <img src="images/product-1.jpg" width="" height=""> --}}
                                        @if (!empty($cartProduct->options->productImage->image))
                                            <img src="{{asset('uploads/products/'.$cartProduct->options->productImage->image)}}" alt="">
                                        @endif
                                        <h2>{{$cartProduct->name}}</h2>
                                    </div>
                                </td>
                                <td>₹{{$cartProduct->price}}</td>
                                <td>
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub " data-id="{{$cartProduct->rowId}}" id="cart_q_s_{{$cartProduct->id}}">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm  border-0 text-center" value={{$cartProduct->qty}} id="cart_q_{{$cartProduct->id}}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{$cartProduct->rowId}}" id="cart_q_a_{{$cartProduct->id}}">
                                                <i class="fa fa-plus "></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td id="total_{{$cartProduct->id}}">
                                    ₹{{$cartProduct->price * $cartProduct->qty}}
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deleteCart('{{$cartProduct->rowId}}')" ><i class="fa fa-times"></i></button>
                                </td>
                            </tr>    
                            @endforeach                           
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">            
                <div class="card cart-summery">
                    
                    <div class="card-body">
                        <div class="sub-title">
                            <h2 class="bg-white">Cart Summery</h3>
                        </div> 
                        <div class="d-flex justify-content-between pb-2">
                            <div>Subtotal</div>
                            <div id="cart-subtotal">₹{{Cart::subtotal()}}</div>
                        </div>
                       
                        <div class="pt-3">
                            <a href="{{route('front.checkout')}}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>     
                {{-- <div class="input-group apply-coupan mt-4">
                    <input type="text" placeholder="Coupon Code" class="form-control">
                    <button class="btn btn-dark" type="button" id="button-addon2">Apply Coupon</button>
                </div>  --}}
            </div>
            @else
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body mt-5 mb-5 aligh-item-center justify-content-center d-flex">
                        <h4>Your Cart is Empty</h4>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection

@section('js')
<script>
    $(".add").on("click",function(){
        var qtyEle = $(this).parent().prev();
        var qtyVal = parseInt(qtyEle.val());
        if(qtyVal < 10){
            qtyEle.val(qtyVal + 1);
            var rowId = $(this).data("id");
            var qty = qtyEle.val();
            updateCart(rowId,qty);
        }
    });

    $(".sub").on("click",function () {
        var qtyEle = $(this).parent().next();
        var qtyVal = parseInt(qtyEle.val());
        if(qtyVal > 1){
            qtyEle.val(qtyVal - 1);
            var rowId = $(this).data("id");
            var qty = qtyEle.val();
            updateCart(rowId,qty);
        }
    });

    function deleteCart(rowid){
        $.ajax({
            url : '{{route("front.deleteCart")}}',
            type : 'post',
            data : {rowId : rowid},
            dataType : 'json',
            success : function(response){
               window.location.href = '{{route("front.cart")}}';
            }
        });
    }
        

    function updateCart(rowId,qty){
        $.ajax({
            url : '{{route("front.updateCart")}}',
            type : 'post',
            data : {rowId : rowId, qty :qty},
            dataType : 'json',
            success : function(response){
                var ele = $("#total_" + response.cart_id);
                var qty = response.cart_qty;
                var price = response.cart_price;
                var total = qty * price;
                var v_qty = response.qty_in_stock;

                if(response.status == true){
                    ele.html("₹"+total);
                    $.ajax({
                        url: '{{route("front.getCartSummary")}}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(summaryResponse) {
                            $('#cart-subtotal').html("₹" + summaryResponse.total);
                            $('#cart-total').html("₹" + summaryResponse.total);
                        },
                    }) 
                }else{
                    alert(response.message);
                }
            }
        })
    }
</script>
@endsection
