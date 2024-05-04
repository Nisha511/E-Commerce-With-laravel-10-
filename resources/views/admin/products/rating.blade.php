@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Products Ratings</h1>
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
            {{-- <form action="" method="GET">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" class="btn btn-default" onclick="window.location.href = '{{route('products.productRatings')}}'">Reset</button>
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
            </form> --}}
            <div class="card-body table-responsive p-0">								
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Username</th>
                            <th>Product Name</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th >Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sr_no = 1;
                        @endphp
                        @foreach ($product_ratings as $rating)
                        <tr>
                            <td>{{$sr_no}}</td>
                            <td>{{$rating->username}}</a></td>
                            <td>{{$rating->title}}</td>
                            <td>{{number_format($rating->rating,2)}} </td>
                            <td>{{$rating->comment}}</td>											
                            <td>
                                @if ($rating->status == 1)
                                    <a href="javascript:void(0);" onclick="ratingStatusChange(0,{{$rating->id}});">
                                        <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                @else 
                                    <a href="javascript:void(0);" onclick="ratingStatusChange(1,{{$rating->id}});">
                                        <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                @endif
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
                {{$product_ratings->links()}}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>

@endsection

@section('js')

<script>
function ratingStatusChange(status,id) {
    if(confirm("Are you sure want to change status")){
        $.ajax({
            url : '{{route("products.changeProductRatings")}}',
            type : 'get',
            data : {status:status, id:id},
            dataType : 'json',
            success : function(response){
                window.location.href = '{{route("products.productRatings")}}';
            }
        })
    }
}
</script>

@endsection