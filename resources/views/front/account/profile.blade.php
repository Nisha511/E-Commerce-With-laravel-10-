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
                @include('front.message')
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <form action="" name="updateProfilForm" id="updateProfilForm" method="post">
                                <div class="mb-3">               
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" value="{{(isset($userInfo->name)) ? $userInfo->name : ''}}" placeholder="Enter Your Name" class="form-control">
                                    <p class="text-danger"></p>
                                </div>
                                <div class="mb-3">            
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" value="{{(isset($userInfo->email)) ? $userInfo->email : ''}}" placeholder="Enter Your Email" class="form-control">
                                    <p class="text-danger"></p>
                                </div>
                                <div class="mb-3">                                    
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{(isset($userInfo->phone)) ? $userInfo->phone : ''}}" placeholder="Enter Your Phone" class="form-control">
                                    <p class="text-danger"></p>
                                </div>
                                <div class="d-flex">
                                    <button class="btn btn-dark">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mt-4 mb-0 pt-2 pb-2">Address</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="container">
                                <form action="" name="updateAddressForm" id="updateAddressForm" method="post">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">               
                                            <label for="first_name">First Name</label>
                                            <input type="text" name="first_name" id="first_name" value="{{(isset($userAddress->first_name)) ? $userAddress->first_name : ''}}" placeholder="Enter Your First Name" class="form-control">
                                            <p class="text-danger"></p>
                                        </div>
                                        <div class="col-md-6 mb-2">               
                                            <label for="last_name">Last Name</label>
                                            <input type="text" name="last_name" id="last_name" value="{{(isset($userAddress->last_name)) ? $userAddress->last_name : ''}}" placeholder="Enter Your Last Name" class="form-control">
                                            <p class="text-danger"></p>
                                        </div>
                                        <div class="col-md-6 mb-2">            
                                            <label for="email">Email</label>
                                            <input type="text" name="email" id="Aemail" value="{{(isset($userAddress->email)) ? $userAddress->email : ''}}" placeholder="Enter Your Email" class="form-control">
                                            <p class="text-danger"></p>
                                        </div>
                                        <div class="col-md-6 mb-2">                                    
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" id="Aphone" value="{{(isset($userAddress->phone)) ? $userAddress->phone : ''}}" placeholder="Enter Your Phone" class="form-control">
                                            <p class="text-danger"></p>
                                        </div>
                                        <div class="mb-2">                                    
                                            <label for="country">Country</label>
                                            @if($countries->isNotEmpty())
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                <option value="{{$country->id}}" {{ isset($userAddress->country_id) && $userAddress->country_id == $country->id ? 'selected' : ''}}>{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger"></p>
                                            @endif
                                        </div>
                                        <div class="mb-2">                                    
                                            <label for="address">Address</label>
                                            <textarea name="address" id="address"  placeholder="Enter Your Address" class="form-control">{{(isset($userAddress->address)) ? $userAddress->address: ''}}</textarea>
                                            <p class="text-danger"></p>
                                        </div>
                                        <div class="col-md-6 mb-2">                                    
                                            <label for="apartment">Appartment</label>
                                            <input type="text" name="apartment" id="apartment" value="{{(isset($userAddress->apartment)) ? $userAddress->apartment : ''}}" placeholder="Enter Your Appartment" class="form-control">
                                            <p class="text-danger"></p>
                                        </div>
                                        <div class="col-md-6 mb-2">                                    
                                            <label for="city">City</label>
                                            <input type="text" name="city" id="city" value="{{(isset($userAddress->city)) ? $userAddress->city : ''}}" placeholder="Enter Your City" class="form-control">
                                            <p class="text-danger"></p>
                                        </div>
                                        <div class="col-md-6 mb-2">                                    
                                            <label for="state">State</label>
                                            <input type="text" name="state" id="state" value="{{(isset($userAddress->state)) ? $userAddress->state : ''}}" placeholder="Enter Your State" class="form-control">
                                            <p class="text-danger"></p>
                                        </div>
                                        <div class="col-md-6 mb-2">                                    
                                            <label for="zip">Zip</label>
                                            <input type="text" name="zip" id="zip" value="{{(isset($userAddress->zip)) ? $userAddress->zip : ''}}" placeholder="Enter Your Zip Code" class="form-control">
                                            <p class="text-danger"></p>
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-dark">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div> 
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')

<script>
    $("#updateProfilForm").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            url : "{{route('account.updateProfile')}}",
            type : 'post',
            data : $(this).serializeArray(),
            dataType : 'json',
            success : function(response){
                if(response.status == true){
                    window.location.href="{{route('account.profile')}}";
                    $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }else{
                    var errors = response['errors'];
                    // console.log(err);
                    if(errors['name']){
                        $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                    }else{
                        $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
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
            }
        });
    });

    $("#updateAddressForm").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            url : "{{route('account.updateAddress')}}",
            type : 'post',
            data : $(this).serializeArray(),
            dataType : 'json',
            success : function(response){
                if(response.status == true){
                    window.location.href="{{route('account.profile')}}";
                    $("#first_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#last_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#Aemail").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#Aphone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#country").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#state").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#address").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#city").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $("#zip").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }else{
                    var errors = response['errors'];
                    if(errors.first_name){
                        $("#first_name").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.first_name);
                    }else{
                        $("#first_name").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.last_name){
                        $("#last_name").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.last_name);
                    }else{
                        $("#last_name").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.email){
                        $("#Aemail").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.email);
                    }else{
                        $("#Aemail").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.country){
                        $("#country").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.country);
                    }else{
                        $("#country").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.address){
                        $("#address").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.address);
                    }else{
                        $("#address").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.city){
                        $("#city").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.city);
                    }else{
                        $("#city").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.phone){
                        $("#Aphone").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.phone);
                    }else{
                        $("#Aphone").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.state){
                        $("#state").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.state);
                    }else{
                        $("#state").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.zip){
                        $("#zip").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.zip);
                    }else{
                        $("#zip").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if(errors.mobile){
                        $("#mobile").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.mobile);
                    }else{
                        $("#mobile").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }
                }
            }
        });
    });
</script>

@endsection