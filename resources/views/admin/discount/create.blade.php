@extends('admin.layout.app')

@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Discount Coupon</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('discount-coupon.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
            <form action="" id="discountCouponForm" name="discountCouponForm" method="post">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code">Code</label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Discount Code">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Discount Coupon Name">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" cols="30" rows="4" class="form-control" placeholder="Description"></textarea>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_uses">Max Uses</label>
                                <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses">	
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="max_uses_user">Max Uses User</label>
                                <input type="number" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max Uses User">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="percent">Percent</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_amount">Discount Amount</label>
                                <input type="number" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_amount">Min Amount</label>
                                <input type="number" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="starts_at">Starts at</label>
                                <input type="text" name="starts_at" id="starts_at" class="form-control" placeholder="Start AT">	
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expires_at">Expires at</label>
                                <input type="text" name="expires_at" id="expires_at" class="form-control" placeholder="Expires AT">	
                                <p></p>
                            </div>
                        </div>											
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="{{route('discount-coupon.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </div>
            </form>			
        </div>
    </div>
    <!-- /.card -->
</section>

@endsection

@section('js')
<script>
$(document).ready(function(){
    $('#starts_at').datetimepicker({
        format:'Y-m-d H:i:s',
    });
    $('#expires_at').datetimepicker({
        format:'Y-m-d H:i:s',
    });
});

$("#discountCouponForm").submit(function(e){
    e.preventDefault();
    var element = $(this);
    $.ajax({
        url : '{{route("discount-coupon.store")}}',
        type : 'post',
        data : element.serializeArray(),
        dataType : 'json',
        success:function(response){
            if(response['status'] == true){
                window.location.href="{{route('discount-coupon.index')}}";
                $("#code").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#discount_amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#type").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#status").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#starts_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#expires_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
            }else{
                var errors = response['errors'];
                // console.log(err);
              if(errors['code']){
                $("#code").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['code']);
              }else{
                $("#code").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['discount_amount']){
                $("#discount_amount").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['discount_amount']);
              }else{
                $("#discount_amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['type']){
                $("#type").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['type']);
              }else{
                $("#type").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['status']){
                $("#status").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['status']);
              }else{
                $("#status").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['starts_at']){
                $("#starts_at").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['starts_at']);
              }else{
                $("#starts_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['expires_at']){
                $("#expires_at").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['expires_at']);
              }else{
                $("#expires_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
            }
        },
        error:function(jqXHR, exceptions){
            console.log("Something went wrong");
        }
    })
})
// $(document).ready(function() {
//     $("#subCategoryForm").submit(function(e) {
//         e.preventDefault();
//         console.log("Form is submitting!");
//         // Add additional code or alerts here if needed
//     });
// });

</script>
@endsection