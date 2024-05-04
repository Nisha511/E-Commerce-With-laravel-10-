<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    //
    public function index(Request $request){
        $sub_categories = SubCategory::select('sub_categories.*', 'categories.name as category_name')
                        ->latest('sub_categories.id')
                        ->leftJoin('categories','categories.id','sub_categories.category_id');
        if(!empty($request->get('table_search'))){
            $sub_categories = $sub_categories->where('sub_categories.name','like','%'.$request->get('table_search').'%');
            $sub_categories = $sub_categories->orWhere('categories.name','like','%'.$request->get('table_search').'%');
        }
      
        $sub_categories = $sub_categories->paginate(10);
        return view('admin.subcategory.list',['sub_categories'=>$sub_categories]);
    }
    public function create(Request $request){
        $categories = Category::orderBy('name')->get();
        return view('admin.subcategory.create',['categories'=>$categories]);
    }
    public function store(Request $request){
       $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:sub_categories',
            'category'=>'required',
            'status'=>'required',
       ]);

       if($validator->passes()){
            $sub_category = new SubCategory();
            $sub_category->name = $request->name;
            $sub_category->slug = $request->slug;
            $sub_category->category_id = $request->category;
            $sub_category->status = $request->status;
            $sub_category->showHome = $request->showHome;
            $sub_category->save();
            $request->session()->flash('success','Sub Category Added');
            return response()->json([
                'status' => true,
                'message' => 'Sub category added.'
            ]);
            
       }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
       }
    }
    public function edit(Request $request, string $id){
        $sub_category = SubCategory::find($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.subcategory.edit',['sub_category'=>$sub_category,'categories'=>$categories]);
    }
    public function update(Request $request, string $id){
        $sub_category = SubCategory::find($id);
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:sub_categories,slug,'.$sub_category->id,
            'category'=>'required',
            'status'=>'required',
       ]);

       if($validator->passes()){
            $sub_category->name = $request->name;
            $sub_category->slug = $request->slug;
            $sub_category->category_id = $request->category;
            $sub_category->status = $request->status;
            $sub_category->showHome = $request->showHome;
            $sub_category->save();
            $request->session()->flash('success','Sub Category Update Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Sub category Update Successfully.'
            ]);
       }else{
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
       }
    }
    public function destroy(string $id, Request $request){
        $sub_category = SubCategory::find($id);
        if(!$sub_category){
            return response()->json([
                'status'=>false,
                'message'=>'Sub Category not found.'
            ]);
        }
        if($sub_category){
            $sub_category->delete();
            $request->session()->flash('success','Sub Category Deleted succesfully.');
            return redirect()->route('sub-categories.index');    
        }
    }
}
