@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipping Management</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('shipping.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="shippingForm" name="shippingForm">
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>    
                                    @if ($countries->isNotEmpty())
                                        @foreach ($countries as $country)
                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                        <option value="rest_of_world">Rest of the World</option>
                                    @endif
                                    </select>	
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">	
                                    <p></p>
                                </div>
                            </div>	
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <button class="btn btn-primary" id="submitButton">Create</button>
                                </div>
                            </div>		
                        </div>
                    </div>							
                </div>
            </div>
        </form>
    <!-- /.card -->
</section>

@endsection

@section('js')
<script>
$("#shippingForm").submit(function(e){
    e.preventDefault();
    var element = $(this);
    $.ajax({
        url : '{{route("shipping.store")}}',
        type : 'post',
        data : element.serializeArray(),
        dataType : 'json',
        success:function(response){
            if(response['status'] == true){
                window.location.href="{{route('shipping.index')}}";
                // $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                // $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
            }else{
                var errors = response['errors'];
                // console.log(err);
              if(errors['country']){
                $("#country").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['country']);
              }else{
                $("#country").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }
              if(errors['amount']){
                $("#amount").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['amount']);
              }else{
                $("#amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
              }

            }
        },
        error:function(jqXHR, exceptions){
            console.log("Something went wrong");
        }
    })
})

</script>
@endsection