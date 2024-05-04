<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    //
    public function index(){
        $shipping_charges = ShippingCharges::leftJoin('countries', 'countries.id', '=', 'shipping_charges.country_id')
                            ->select('shipping_charges.id as shipping_charge_id', 'countries.name', 'shipping_charges.amount')
                            ->get();

        // $countries = Country::get();
        // dd($shipping_charges);
        return view('admin.shipping.list',[
            'shipping_charges' => $shipping_charges,
        ]);
    }
    public function create(){
        $countries = Country::get();
        return view('admin.shipping.create',[
            'countries' => $countries
        ]);
    }
    public function store(Request $request){
        $count = ShippingCharges::where('country_id',$request->country)->count();

        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if($validator->passes()){

            if($count > 0){
                $request->session()->flash('error','For this country already shipping is created.');
                return response()->json([
                    'status' => true
                ]);
            }
            
            $shipping = new ShippingCharges();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();
            $request->session()->flash('success','Shipping Charge added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Shipping Charge added successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Some problem'
            ]);
        }
    }
    public function edit($id){
        $shipping_charge = ShippingCharges::find($id);
        // dd($shipping_charge);
        $countries = Country::get();
        return view('admin.shipping.edit',[
            'countries' => $countries,
            'shipping_charge' => $shipping_charge
        ]);
    }
    public function update(Request $request, $id){
        $count = ShippingCharges::where('country_id',$request->country)->whereNot('id',$id)->count();
        $shipping_charge = ShippingCharges::find($id);
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if($validator->passes()){

            if($count > 0){
                $request->session()->flash('error','For this country already shipping is created.');
                return response()->json([
                    'status' => true
                ]);
            }
            $shipping_charge->country_id = $request->country;
            $shipping_charge->amount = $request->amount;
            $shipping_charge->save();
            $request->session()->flash('success','Shipping Charge Update successfully');
            return response()->json([
                'status' => true,
                'message' => 'Shipping Charge Update successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Some problem'
            ]);
        }
    }
    public function destroy(string $id, Request $request){
        $shipping_charge = ShippingCharges::find($id);
        if(!$shipping_charge){
            return response()->json([
                'status'=>false,
                'message'=>'Shipping Charge not found.'
            ]);
        }
        if($shipping_charge){
            $shipping_charge->delete();
            $request->session()->flash('success','Shipping Charge Deleted succesfully.');
            return redirect()->route('shipping.index');    
        }
    }
}
