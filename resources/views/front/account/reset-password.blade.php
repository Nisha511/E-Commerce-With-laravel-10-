@extends('front.layout.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item">Login</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">
                {{Session::get('error')}}
            </div>
        @endif
        <div class="login-form">    
            <form action="{{route('account.processResetPassword',$token)}}" method="post" name="loginForm" id="loginForm">
                @csrf
                <input type="hidden" name="token" id="token" value="{{$token}}">
                <h4 class="modal-title">Reset Password</h4>
                <div class="form-group">
                    <input type="password" value="{{old('password')}}" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" id="password">
                    @error('password')
                        <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" value="{{old('confirm_password')}}" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password" name="confirm_password" id="confirm_password">
                    @error('confirm_password')
                        <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                </div>
                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Submit">              
            </form>	
        </div>
    </div>
</section>

@endsection

@section('js')

<script>
   
</script>

@endsection