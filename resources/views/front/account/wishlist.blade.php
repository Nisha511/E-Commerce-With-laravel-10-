@extends('front.layout.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
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
                    @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        {{Session::get('error')}}
                    </div>
                    @endif
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{Session::get('success')}}
                    </div>
                    @endif
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">My Orders</h2>
                    </div>
                    @if ($wishlists->isNotEmpty())
                    <div class="card-body p-4">
                        @foreach ($wishlists as $wishlist)
                        <div class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                            <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                @php
                                    $productImage = getProductImage($wishlist->product_id);
                                @endphp
                                <a class="d-block flex-shrink-0 mx-auto me-sm-4" href="{{route('front-product',$wishlist->product->slug)}}" style="width: 10rem;">
                                    {{-- <img src="images/product-1.jpg" alt="Product"> --}}
                                    <img src="{{asset('uploads/products/'.$productImage->image)}}" alt="Product">
                                </a>
                                <div class="pt-2">
                                    <h6 class="product-title fs-base mb-2"><a href="{{route('front-product',$wishlist->product->slug)}}">{{$wishlist->product->name}}</a></h56>                                        
                                    <div class="fs-lg text-accent pt-2">₹{{number_format($wishlist->product->price,2)}}</div>
                                </div>
                            </div>
                            <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                <button onclick="removeProduct({{$wishlist->id}})" class="btn btn-outline-danger btn-sm" type="button"><i class="fas fa-trash-alt me-2"></i>Remove</button>
                            </div>
                        </div>    
                        @endforeach
                        </div>
                    @else
                    <div class="card-body p-4"> 
                        Your WishList is empty.
                    </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')

<script>
    function removeProduct(id){
        if(confirm('Are you sure want to Remove this product from WishList ?')){
            $.ajax({
                url :'{{route("account.RemoveProductFromWishlist")}}',
                type : 'post',
                data : {id : id},
                dataType : 'json',
                success : function(res){
                    window.location.href = '{{route("account.wishList")}}';
                }
            })
        }
    }
</script>

@endsection