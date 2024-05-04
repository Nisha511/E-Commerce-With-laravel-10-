@extends('front.layout.app')

@section('content')

<div class="container">
    <div class="col-md-12 text-center mt-5">
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif 
        <h1>Thank You !</h1>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
        <p>Your order id : {{$id}}</p>
        @php
            if(isset($_GET['session_id'])){
                $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
                $session_id = $_GET['session_id'];
                $stripeSession = $stripe->checkout->sessions->retrieve($session_id);
                echo "<p>Your payment has been successfully processed.</p>";
                echo "<p>Payment ID: " . $stripeSession->id . "</p>";
            }
        @endphp
    </div>
</div>

@endsection
