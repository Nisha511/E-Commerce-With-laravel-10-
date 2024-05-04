<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    //
    public function index(Request $request){
        $brands = Brand::latest();
        if(!empty($request->get('table_search'))){
            $brands = $brands->where('name','like','%'.$request->get('table_search').'%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brand.list',['brands'=>$brands]);
    }
    public function create(){
        return view('admin.brand.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands'
        ]);

        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand Added Successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Brand Added Successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit(Request $request, string $id){
        $brand = Brand::find($id);
        return view('admin.brand.edit',['brand'=>$brand]);
    }
    public function update(Request $request, string $id){
        $brand = Brand::find($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id
        ]);

        if($validator->passes()){
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand Updated Successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Brand Updated Successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy(string $id,Request $request){
        $brand = Brand::find($id);
        if(!$brand){
            return response()->json([
                'status'=>false,
                'message'=>'Brand not found.'
            ]);
        }
        if($brand){
            $brand->delete();
            $request->session()->flash('success','Brand Deleted succesfully.');
            return redirect()->route('brands.index');    
        }
    }
}
