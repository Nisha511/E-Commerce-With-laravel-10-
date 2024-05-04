<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    //
    public function index(Request $request){
        $discountCoupons = DiscountCoupon::latest();
        if(!empty($request->get('table_search'))){
            $discountCoupons = $discountCoupons->where('name','like','%'.$request->get('table_search').'%')
                                                ->orWhere('code','like','%'.$request->get('table_search').'%');
        }
        $discountCoupons = $discountCoupons->paginate(10);
        return view('admin.discount.list',['discountCoupons'=>$discountCoupons]);
    }
    public function create(){
        return view('admin.discount.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required'
        ]);

        if($validator->passes()){
            //validation for start at date
            if(!empty($request->starts_at)){
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                if($startAt->lte($now) == true){
                    return response()->json([
                        'status' => false,
                        'errors' => ["starts_at" => "Start date not should be less than current date time"],
                    ]);
                }
            }
            //validation for expire data 
            if(!empty($request->starts_at) && !empty($request->expires_at)){
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                $expireAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
                if($expireAt->gt($startAt) == false){
                    return response()->json([
                        'status' => false,
                        'errors' => ["expires_at" => "Expire date must be greate than start date"],
                    ]);
                }
            }
            $discount_coupon = new DiscountCoupon();
            $discount_coupon->code = $request->code;
            $discount_coupon->discount_amount = $request->discount_amount;
            $discount_coupon->type = $request->type;
            $discount_coupon->status = $request->status;
            if(isset($request->name)){
                $discount_coupon->name = $request->name;
            }
            if(isset($request->description)){
                $discount_coupon->description = $request->description;
            }
            if(isset($request->max_uses)){
                $discount_coupon->max_uses = $request->max_uses;
            }
            if(isset($request->max_uses_user)){
                $discount_coupon->max_uses_user = $request->max_uses_user;
            }
            if(isset($request->min_amount)){
                $discount_coupon->min_amount = $request->min_amount;
            }
            if(isset($request->starts_at)){
                $discount_coupon->starts_at = $request->starts_at;
            }
            if(isset($request->expires_at)){
                $discount_coupon->expires_at = $request->expires_at;
            }
            $discount_coupon->save();
            $request->session()->flash('success','Discount Coupon Add successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Discount Coupon Add successfully.',
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Validation not work',
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit(string $id){
        $discountCoupon = DiscountCoupon::find($id);
        return view('admin.discount.edit',['discountCoupon' => $discountCoupon]);
    }
    public function update(Request $request, string $id){
        $discount_coupon = DiscountCoupon::find($id);
        
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required'
        ]);

        if($validator->passes()){
            //validation for start at date
            if(!empty($request->starts_at)){
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                if($startAt->lte($now) == true){
                    return response()->json([
                        'status' => false,
                        'errors' => ["starts_at" => "Start date not should be less than current date time"],
                    ]);
                }
            }
            //validation for expire data 
            if(!empty($request->starts_at) && !empty($request->expires_at)){
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                $expireAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
                if($expireAt->gt($startAt) == false){
                    return response()->json([
                        'status' => false,
                        'errors' => ["expires_at" => "Expire date must be greate than start date"],
                    ]);
                }
            }
            $discount_coupon->code = $request->code;
            $discount_coupon->discount_amount = $request->discount_amount;
            $discount_coupon->type = $request->type;
            $discount_coupon->status = $request->status;
            if(isset($request->name)){
                $discount_coupon->name = $request->name;
            }
            if(isset($request->description)){
                $discount_coupon->description = $request->description;
            }
            if(isset($request->max_uses)){
                $discount_coupon->max_uses = $request->max_uses;
            }
            if(isset($request->max_uses_user)){
                $discount_coupon->max_uses_user = $request->max_uses_user;
            }
            if(isset($request->min_amount)){
                $discount_coupon->min_amount = $request->min_amount;
            }
            if(isset($request->starts_at)){
                $discount_coupon->starts_at = $request->starts_at;
            }
            if(isset($request->expires_at)){
                $discount_coupon->expires_at = $request->expires_at;
            }
            $discount_coupon->save();
            $request->session()->flash('success','Discount Coupon Update successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Discount Coupon Update successfully.',
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Validation not work',
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy(Request $request, string $id){
        $discountCoupon = DiscountCoupon::find($id);
        if(!$discountCoupon){
           return redirect()->route('discount-coupon.index')->with('error','Discount Coupon Not Found.');
        }
        if($discountCoupon){
            $discountCoupon->delete();
            return redirect()->route('discount-coupon.index')->with('success','Discount Coupon Delete succesfully.');    
        }
    }

}
