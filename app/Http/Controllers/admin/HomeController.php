<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    //
    public function index(){
        $data['total_orders'] = Order::where('status','!=','cancelled')->count();
        $data['total_products'] = Product::count();
        $data['total_users'] = User::where('role',1)->count();
        $data['total_revenue'] = Order::where('status','!=','cancelled')->sum('grand_total');
        $startdate = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        // DB::enableQueryLog();
        $data['total_revenue_of_this_month'] = Order::where('status','!=','cancelled')
                                                ->where('created_at','>=',$startdate)
                                                ->where('created_at','<=',$currentDate)
                                                ->sum('grand_total');
                                            
        // $quries = DB::getQueryLog();
        // dd($quries);
        $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d H:i:s');
        $lastDayofPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d H:i:s');
        $data['total_revenue_of_last_month'] = Order::where('status','!=','cancelled')
                                                ->where('created_at','>=',$firstDayofPreviousMonth)
                                                ->where('created_at','<=',$lastDayofPreviousMonth)
                                                ->sum('grand_total');

        $lat30days = Carbon::today()->subDays(30)->format('Y-m-d H:i:s');
        $data['total_revenue_of_last_30_days'] = Order::where('status','!=','cancelled')
                                                ->where('created_at','>=',$lat30days)
                                                ->sum('grand_total');
        return view('admin.dashboard',$data);
    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success','You are Logout Successfully');
    }
    public function changePassword(){
        return view('admin.changePassword');
    }
    public function updatePassword(Request $request){
        // dd($request);
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
            }else{
                $user->password = Hash::make($request->new_password);
                $user->save();
                if($user){
                    session()->flash('success','Password changed successfully.');
                    return response()->json([
                        'status' => true,
                        'message' => 'Admin side password change successsfull.',
                    ]);
                }else{
                    session()->flash('error','Something went wrong while changing password.');
                    return response()->json([
                        'status' => true,
                        'message' => 'SOmething went wrong shile cahnging admin side password',
                    ]);
                }
            }
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
}
