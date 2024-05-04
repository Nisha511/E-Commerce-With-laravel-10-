@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create User</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('user.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="userForm" name="userForm">
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email">	
                                    <p></p>
                                </div>
                            </div>	
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">	
                                    <p></p>
                                </div>
                            </div>	
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone">	
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
                        </div>
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" id="submitButton">Create</button>
                    <a href="{{route('user.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
    <!-- /.card -->
</section>

@endsection

@section('js')
<script>

$("#userForm").submit(function(e){
    e.preventDefault();
    var element = $(this);
    $.ajax({
        url : '{{route("user.store")}}',
        type : 'post',
        data : element.serializeArray(),
        dataType : 'json',
        success:function(response){
            if(response['status'] == true){
                window.location.href="{{route('user.index')}}";
                $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
            }else{
                var errors = response['errors'];
                // console.log(err);
                if(errors['name']){
                    $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                }else{
                    $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }

                if(errors['password']){
                    $("#password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['password']);
                }else{
                    $("#password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }

                if(errors['email']){
                    $("#email").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['email']);
                }else{
                    $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }

                if(errors['phone']){
                    $("#phone").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['phone']);
                }else{
                    $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
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