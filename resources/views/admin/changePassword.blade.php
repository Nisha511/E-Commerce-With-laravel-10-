@extends('admin.layout.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Change Password</h1>
            </div>
            <div class="col-sm-6">
                
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('message')
        <form action="" autocomplete="off" method="post" name="changePasswordForm" id="changePasswordForm">        
            <div class="card">
                <div class="card-body">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Old Password</label>
                            <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                            <p class="text-danger"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">New Password</label>
                            <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                            <p class="text-danger"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Old Password" class="form-control">
                            <p class="text-danger"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button class="btn btn-dark">Save</button>
            </div>
        </form>    
    </div>
</section>

@endsection

@section('js')
<script>
    $("#changePasswordForm").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            url : '{{route("admin.updatePassword")}}',
            type : 'post',
            data : $(this).serializeArray(),
            dataType : 'json',
            success : function(response){
                if(response.status == true){
                    window.location.href="{{route('admin.changePassword')}}";
                    $("#old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#new_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#Aemconfirm_passwordail").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }else{
                    var errors = response['errors'];
                    if(errors.old_password){
                        $("#old_password").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.old_password);
                    }else{
                        $("#old_password").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.new_password){
                        $("#new_password").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.new_password);
                    }else{
                        $("#new_password").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.confirm_password){
                        $("#confirm_password").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.confirm_password);
                    }else{
                        $("#confirm_password").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }
                }
            }
        })
    })
</script>

@endsection