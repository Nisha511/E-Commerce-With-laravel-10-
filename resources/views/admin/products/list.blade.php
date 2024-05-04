@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Products</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('products.create')}}" class="btn btn-primary">New Product</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

@include('message')
<!-- Main content -->

<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
            <form action="" method="GET">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" class="btn btn-default" onclick="window.location.href = '{{route('products.index')}}'">Reset</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input value="{{Request::get('table_search')}}" type="text" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                          </div>
                    </div>
                </div>
            </form>
            <div class="card-body table-responsive p-0">								
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th width="80"></th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>SKU</th>
                            <th width="100">Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sr_no = 1;
                        @endphp
                        @foreach ($products as $product)
                        <tr>
                            <td>{{$sr_no}}</td>
                            @php
                                $productImage = $product->product_images->first();
                            @endphp
                            <td>
                                @if (!empty($productImage->image))
                                    <img src="{{asset('uploads/products/'.$productImage->image)}}" class="img-thumbnail" width="50" ></td>
                                @else
                                <img src="{{asset('admin-assets/img/default-150x150.png')}}" alt="" class="img-thumbnail" width="50">
                                @endif
                            <td><a href="#">{{$product->name}}</a></td>
                            <td>{{$product->price}}</td>
                            <td>{{!empty($product->qty) ? $product->qty.' left in stock' : '-'}} </td>
                            <td>{{$product->sku}}</td>											
                            <td>
                                @if ($product->status == 1)
                                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else 
                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </td>
                            <td>
                                <a href="{{route('products.edit',$product->id)}}">
                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <a href="{{route('products.destroy',$product->id)}}" class="text-danger w-4 h-4 mr-1" onclick="return confirm('Are you sure you want to delete this record')">
                                    <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                      </svg>
                                </a>
                            </td>
                        </tr>
                        @php
                            $sr_no++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>										
            </div>
            <div class="card-footer clearfix">
                {{$products->links()}}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>

@endsection

@section('js')

<script>

</script>

@endsection