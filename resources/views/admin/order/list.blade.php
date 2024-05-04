@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Orders</h1>
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
                    <button type="button" class="btn btn-default" onclick="window.location.href = '{{route('adminOrder.index')}}'">Reset</button>
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
                            <th width="60">Order#</th>
                            <th width="100">Customer</th>
                            <th width="100">Email</th>
                            <th width="100">Phone</th>
                            <th width="100">Status</th>
                            <th width="100">Total</th>
                            <th width="100">Date Purchased</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td><a href="{{route('adminOrder-detail.detail',$order->id)}}">{{$order->id}}</a></td>
                            <td>{{$order->first_name}}{{' '}}{{$order->last_name}}</td>
                            <td>{{$order->email}}</td>
                            <td>{{$order->phone}}</td>
                            <td>
                                @if ($order->status == 'delivered')
                                <span class="badge bg-success">Delivered</span>
                                @elseif ($order->status == 'pending')
                                <span class="badge bg-info">Pending</span>
                                @elseif ($order->status == 'shipped')
                                <span class="badge bg-warning">Shipped</span>
                                @else
                                <span class="badge bg-danger">Cancelled</span>
                               @endif
                            </td>
                            <td>₹{{number_format($order->grand_total,2)}}</td>
                            <td>{{\Carbon\Carbon::parse($order->created_at)->format('d M Y')}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>										
            </div>
            <div class="card-footer clearfix">
                {{$orders->links()}}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>

@endsection