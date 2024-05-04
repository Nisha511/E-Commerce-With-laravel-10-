@extends('front.layout.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{route('account.profile')}}">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Order :  {{$order_detail->id}}</h2>
                    </div>

                    <div class="card-body pb-0">
                        <!-- Info -->
                        <div class="card card-sm">
                            <div class="card-body bg-light mb-3">
                                <div class="row">
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Order No: </h6>
                                        <!-- Text -->
                                        <p class="mb-lg-0 fs-sm fw-bold">
                                            {{$order_detail->id}}
                                        </p>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Shipped date:</h6>
                                        <!-- Text -->
                                        <p class="mb-lg-0 fs-sm fw-bold">
                                            <time datetime="2019-10-01">
                                                @if (!empty($order_detail->shipped_date))
                                                    {{\Carbon\Carbon::parse($order_detail->shipped_date)->format('d M, Y')}}
                                                @else
                                                    N/A
                                                @endif
                                            </time>
                                        </p>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Status:</h6>
                                        <!-- Text -->
                                        <p class="mb-0 fs-sm fw-bold">
                                            @if ($order_detail->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @elseif ($order_detail->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($order_detail->status == 'shipped')
                                                <span class="badge bg-info">Shipped</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                           @endif
                                        </p>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Order Amount:</h6>
                                        <!-- Text -->
                                        <p class="mb-0 fs-sm fw-bold">
                                            ₹{{number_format($order_detail->grand_total,2)}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer p-3">

                        <!-- Heading -->
                        <h6 class="mb-7 h5 mt-4">Order Items {{$orderItems->count()}}</h6>

                        <!-- Divider -->
                        <hr class="my-3">

                        <!-- List group -->
                        <ul>
                            @if ($orderItems->isNotEmpty())
                                @foreach ($orderItems as $orderItem)
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-md-3 col-xl-2">
                                            @php
                                                $productImage = getProductImage($orderItem->product_id);
                                            @endphp
                                             @if ($productImage->image != "")
                                             <img src="{{asset('uploads/products/'.$productImage->image)}}" alt="" class="img-fluid">
                                             @endif
                                        </div>
                                        <div class="col">
                                            <!-- Title -->
                                            <p class="mb-4 fs-sm fw-bold">
                                                <a class="text-body" href="product.html">{{$orderItem->name}} x {{$orderItem->qty}}</a> <br>
                                                <span class="text-muted">{{$orderItem->price}}</span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>                      
                </div>
                
                <div class="card card-lg mb-5 mt-3">
                    <div class="card-body">
                        <!-- Heading -->
                        <h6 class="mt-0 mb-3 h5">Order Total</h6>

                        <!-- List group -->
                        <ul>
                            <li class="list-group-item d-flex">
                                <span>Subtotal</span>
                                <span class="ms-auto">₹{{number_format($order_detail->subtotal,2)}}</span>
                            </li>
                            <li class="list-group-item d-flex">
                                <span>Discount {{(!empty($order_detail->coupone_code)) ? '('.$order_detail->coupone_code.')' : ''}}</span>
                                <span class="ms-auto">₹{{number_format($order_detail->discount,2)}}</span>
                            </li>
                            <li class="list-group-item d-flex">
                                <span>Shipping</span>
                                <span class="ms-auto">₹{{number_format($order_detail->shipping,2)}}</span>
                            </li>
                            <li class="list-group-item d-flex fs-lg fw-bold">
                                <span>Total</span>
                                <span class="ms-auto">₹{{number_format($order_detail->grand_total,2)}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')

<script>

</script>

@endsection