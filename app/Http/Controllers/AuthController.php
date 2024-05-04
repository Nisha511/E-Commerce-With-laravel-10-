<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
    public function register(){
        return view('front.account.register');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed'
        ]);
        if($validator->passes()){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            if(isset($request->phone)){
                $user->phone = $request->phone;
            }
            $user->save();
            $request->session()->flash('success','User register successful.');
            return response()->json([
                'status' => true,
                'message' => 'user register successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function login(){
        return view('front.account.login');
    }
    public function login_store(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->passes()){
            if(Auth::attempt(['email'=>$request->email,'password'=>$request->password],$request->get('remember'))){
                if(session()->has('url.intended')){
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');
            }else{
                return redirect()->route('account.login')->with('error','Either Email or password is incorrect');
            }
        }else{
            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }
    public function profile(){
        $userInfo = User::where('id',Auth::user()->id)->first();
        $userAddress = CustomerAddress::where('user_id',$userInfo->id)->first();
        $countries = Country::orderBy('name','ASC')->get();
        $data['userAddress'] = $userAddress;
        $data['userInfo'] = $userInfo;
        $data['countries'] = $countries;
        // dd($userAddress);
        return view('front.account.profile',$data);
    }
    public function updateProfile(Request $request){
        $userInfo = User::where('id',Auth::user()->id)->first();
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$userInfo->id,
            'phone' => 'required'
        ]);
        if($validator->passes()){
            $userInfo->name = $request->name;
            $userInfo->email = $request->email;
            $userInfo->phone = $request->phone;
            $userInfo->save();
            if($userInfo){
                session()->flash('success','Your profile Update Successfully.');
                return response()->json([
                    'status' => true,
                    'message' => 'Your profile Update Successfully.'
                ]);
            }else{
                session()->flash('error','Something went wrong while updating your profile');
                return response()->json([
                    'status' => true,
                    'message' => 'Something went wrong while updating your profile.'
                ]);
            }
            
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'errors' => $validator->errors()
            ]);
        }
    }
    public function updateAddress(Request $request){
        $validator = Validator::make($request->all(),[
            "first_name" => "required",
            "last_name" => "required",
            'email' => 'required|email',
            'country' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' =>'required',
        ]);
        
        if($validator->passes()){
            $user = Auth::user();
            // echo "<PRE>";print_r($user);exit;
            $customer_address = CustomerAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'country_id' => $request->country,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                ]
            );
            if($customer_address){
                $request->session()->flash('success','Your address update successfully.');
                return response()->json([
                    'status' => true,
                    'message' => 'Your address update successfully.',
                ]);
            }else{
                $request->session()->flash('error','Something went while updating address.');
                return response()->json([
                    'status' => true,
                    'message' => 'Something went while updating address.',
                ]);
            }
            
        }else{
            return response()->json([
                'message' => 'Please fix issued',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function logout(){
        Auth::logout();
        Cart::destroy();
        return redirect()->route('account.login')->with('success','You successfully logout');
    }
    public function order(){
        $user_id = Auth::user()->id;
        $orders = Order::where('user_id',$user_id)->orderBy('created_at','DESC')->paginate(10);
        return view('front.account.order',['orders'=>$orders]);
    }
    public function orderDetail(string $id){
        $user_id = Auth::user()->id;
        $order_detail = Order::where('user_id',$user_id)->where('id',$id)->first();
        $orderItems = OrderItem::where('order_id',$id)->get();
        return view('front.account.order-detail', [
            'order_detail'=>$order_detail,
            'orderItems' => $orderItems
        ]);
    }
    public function wishList(){
        $wishlists = Wishlist::where('user_id',Auth::user()->id)->with('product')->get();
        $data['wishlists'] = $wishlists;
        return view('front.account.wishlist',$data);
    }
    public function RemoveProductFromWishlist(Request $request){
        $removeProductWish = Wishlist::where('user_id',Auth::user()->id)->where('id',$request->id)->first();
        if(!$removeProductWish){
            session()->flash('error','Product not found which you are trying to remove from wishlist');
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found which you are trying to remove from wishlist'
            ]);
        }
        if($removeProductWish){
            $removeProductWish->delete();
            session()->flash('success','Product successfully remove from wishlist');
            return response()->json([
                'status' => 'success',
                'message' => 'Product successfully remove from wishlist'
            ]);
        }
    }
    public function changePassword(){
        return view('front.account.changePassword');
    }
    public function updatePassword(Request $request){
        $user = User::where('id',Auth::user()->id)->first();
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);
        if($validator->passes()){    
            if(!Hash::check($request->old_password,$user->password)){
                session()->flash('error','Old password is not matched');
                return response()->json([
                    'status' => true
                ]);
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            if($user){
                session()->flash('success','Password changed successfully.');
                return response()->json([
                    'status' => true
                ]);
            }else{
                session()->flash('error','Something went wrong while changing password.');
                return response()->json([
                    'status' => true
                ]);
            }
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function forgotPasswordForm(){
        return view('front.account.forgot-password');
    }
    public function processForgotPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
        ]);
        if($validator->passes()){
           $token = Str::random(60);

           $q = \DB::table('password_reset_tokens')->where('email',$request->email)->delete();

            $q = \DB::table('password_reset_tokens')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => now()
            ]);

            if($q){
                $user = User::where('email',$request->email)->first();
                $formdata = [
                    'token' => $token,
                    'user' => $user,
                    'mailSubject' => 'You have requested to reset password'
                ];
                Mail::to($request->email)->send(new ResetPasswordEmail($formdata));
                return redirect()->route('account.forgotPasswordForm')->with('success','please check email for reset password');
            }else{
                return redirect()->route('account.forgotPasswordForm')->with('error','Something went wrong while sending mail');
            }
        }else{
            return redirect()->route('account.forgotPasswordForm')->withErrors($validator)->withInput($request->only('email'));
        }
    }
    public function resetPassword($token){
        $token_valid = \DB::table('password_reset_tokens')->where('token',$token)->first();
        // dd($token_valid);
        if($token_valid == null){
            return redirect()->route('account.forgotPasswordForm')->with('error','For Reset password you need to enter email');
        }

        return view('front.account.reset-password',[
            'token' => $token
        ]);
    }
    public function processResetPassword(Request $request, $token){
        $token = $request->token;
        $token_valid = \DB::table('password_reset_tokens')->where('token',$token)->first();
        // dd($token_valid);
        if($token_valid == null){
            return redirect()->route('account.forgotPasswordForm')->with('error','For Reset password you need to enter email');
        }

        $user = User::where('email',$token_valid->email)->first();
        $validator = Validator::make($request->all(),[
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);
        if($validator->passes()){
            $user->password = Hash::make($request->password);
            $user->save();
            if($user){
                return redirect()->route('account.login')->with('success','You Have Successfully Reset Your Password');
            }else{
                return redirect()->route('account.login')->with('error','Something went wrong while reset pasword');
            }
        }else{
            return redirect()->route('account.resetPassword',$token)->withErrors($validator);
        }
    }
}
