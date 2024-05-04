@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Order: #{{$order_detail->id}}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('adminOrder.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    @include('message')
    <!-- Default box -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                            <h1 class="h5 mb-3">Shipping Address</h1>
                            <address>
                                <strong>{{$order_detail->first_name .' '.$order_detail->last_name}}</strong><br>
                                {{$order_detail->address}}<br>
                                {{ucfirst(trans($order_detail->city)) .' '.ucfirst(trans($order_detail->state))}},{{' '.ucfirst(trans($order_detail->country_name))}}<br>
                                Phone: {{$order_detail->phone}}<br>
                                Email: {{$order_detail->email}}<br>                                
                                <strong>Shipped Date : {{!empty($order_detail->shipped_date) ? \Carbon\Carbon::parse($order_detail->shipped_date)->format("d M Y H:i:s") : 'N/A'}}</strong>
                            </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <b>Invoice #007612</b><br>
                                <br>
                                <b>Order ID:</b> {{$order_detail->id}}<br>
                                <b>Total:</b> ₹{{number_format($order_detail->grand_total,2)}}<br>
                                <b>Status:</b> 
                                @if ($order_detail->status == 'delivered')
                                    <span class="text-success" id="status_d">Delivered</span>
                                @elseif ($order_detail->status == 'pending')
                                    <span class="text-warning" id="status_p">Pending</span>
                                @elseif ($order_detail->status == 'shipped')
                                    <span class="text-info" id="status_s">Shipped</span>
                                @else
                                    <span class="text-danger" id="status_c">Cancelled</span>
                                @endif
                                
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">								
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th width="100">Price</th>
                                    <th width="100">Qty</th>                                        
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($orderItem->isNotEmpty())
                                @foreach ($orderItem as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>₹{{number_format($item->price,2)}}</td>                                        
                                    <td>{{$item->qty}}</td>
                                    <td>₹{{number_format($item->total,2)}}</td>
                                </tr>
                                @endforeach
                                @endif
                                
                                <tr>
                                    <th colspan="3" class="text-right">Subtotal:</th>
                                    <td>₹{{number_format($order_detail->subtotal,2)}}</td>
                                </tr>
                                @if($order_detail->discount > 0)
                                <tr>
                                    <th colspan="3" class="text-right">Discount: {{'('.$order_detail->coupone_code.')'}}</th>
                                    <td>₹{{number_format($order_detail->discount,2)}}</td>
                                </tr>                                
                                @endif
                                <tr>
                                    <th colspan="3" class="text-right">Shipping:</th>
                                    <td>₹{{number_format($order_detail->shipping,2)}}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Grand Total:</th>
                                    <td>₹{{number_format($order_detail->grand_total,2)}}</td>
                                </tr>
                            </tbody>
                        </table>								
                    </div>                            
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="alert alert-success alert-dismissible" style="display: none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5 id="status_order_success"><i class="icon fas fa-check"></i></h5>
                        <span id="error_updateorder"></span>
                    </div>
                    <div class="alert alert-danger alert-dismissible" style="display: none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5 id="status_order_error"><i class="icon fas fa-check"></i></h5>
                        <span id="error_updateorder"></span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span id="order_status" class="text-success"></span>
                        </div>
                        <h2 class="h4 mb-3">Order Status</h2>
                        <div class="mb-3">
                            <select name="status" id="status" class="form-control">
                                <option value="pending" {{($order_detail->status == 'pending') ? 'selected':''}}>Pending</option>
                                <option value="shipped" {{($order_detail->status == 'shipped') ? 'selected':''}}>Shipped</option>
                                <option value="delivered" {{($order_detail->status == 'delivered') ? 'selected':''}}>Delivered</option>
                                <option value="cancelled" {{($order_detail->status == 'cancelled') ? 'selected':''}}>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                           <label for="">Shipped Date</label>
                           <input placeholder="Shipped Date" type="text" name="shipped_date" id="shipped_date" class="form-control" value="{{$order_detail->shipped_date}}">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" id="updateOrderStatus">Update</button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <form action="" name="sendInvoiceForm" id="sendInvoiceForm" method="post">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Send Inovice Email</h2>
                            <div class="mb-3">
                                <select name="userType" id="userType" class="form-control">
                                    <option value="customer">Customer</option>                                                
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" >Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>

@endsection

@section('js')

<script>
    $(document).ready(function(){
    $('#shipped_date').datetimepicker({
        format:'Y-m-d H:i:s',
    });
});

$("#updateOrderStatus").on("click", function(){
    var status = $("#status").val();
    var shippedDate = $("#shipped_date").val();
    if(confirm('Are you sure want to sent Email ?')){
        $.ajax({
            url : '{{route("admin-order.updateOrdeStatus",$order_detail->id)}}',
            type : 'post',
            data : {'status' : status, 'Sdate' : shippedDate},
            dataType : 'json',
            success : function(response){
                if(response.status == 'Success'){
                    console.log(response.order_status);
                    $(".alert-success").show();
                    $("#status_order_success").html(response.status);
                    if(response.order_status == 'Delivered'){
                        $("#status_d").html(response.order_status);
                    }else if(response.order_status == 'Pending'){
                        $("#status_p").html(response.order_status);
                    }else if(response.order_status == 'Shipped'){
                        $("#status_s").html(response.order_status);
                    }else{
                        $("#status_c").html(response.order_status);
                    }
                    
                    $("#error_updateorder").html(response.message);
                }else{
                    $(".alert-danger").show();
                    $("#status_order_error").html(response.status);
                    $("#error_updateorder").html(response.message);
                    $("#error_updateorder").html(response.message);
                }
            }
        })
    }
})

$("#sendInvoiceForm").on("submit", function(e){
    e.preventDefault();
    if(confirm('Are you sure want to sent Email ?')){
        $.ajax({
            url : '{{route("admin-order.sendInvoiceMessage",$order_detail->id)}}',
            type : 'post',
            data : $(this).serializeArray(),
            dataType : 'json',
            success : function(response){
                window.location.href = '{{route("adminOrder-detail.detail",$order_detail->id)}}';
            }
        })
    }
    
})
</script>

@endsection