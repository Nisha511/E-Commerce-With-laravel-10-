<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    //
    public function index(Request $request){
        $categories = Category::latest();
        if(!empty($request->get('table_search'))){
            $categories = $categories->where('name','like','%'.$request->get('table_search').'%');
        }
        $categories = $categories->paginate(10);
        return view('admin.category.list',['categories'=>$categories]);
    }
    public function create(){
        return view('admin.category.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:categories',
        ]);

        if($validator->passes()){
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                $new_image_name = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$new_image_name;
                File::copy($sPath,$dPath);
                $category->image = $new_image_name;
                $category->save();
            }
            $request->session()->flash('success','Category added succesfully.');
            return response()->json([
                'status' =>true,
                'message'=>'Category added succesfully.',
             ]);
        }else{
            return response()->json([
               'status' =>false,
               'errors'=>$validator->errors(),
            ]);
        }
    }
    public function edit(string $id, Request $request){
        $category = Category::find($id);
        // echo "<PRE>";print_r($category);exit;
        if(!$category){
            return response()->json([
                'status' => false,
                'message' => 'Category not found.'
            ]);
        }
        return view('admin.category.edit',['category'=>$category]);
    }
    public function update(string $id, Request $request){
        $category = Category::find($id);
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug' => 'required|unique:categories,slug,' . $category->id,
        ]);

        if($validator->passes()){
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $oldimage = $category->image;
            $category->save();
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                $new_image_name = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$new_image_name;
                File::copy($sPath,$dPath);
                File::delete(public_path().'/uploads/category/'.$oldimage);
                $category->image = $new_image_name;
                $category->save();
            }
            $request->session()->flash('success','Category Updated succesfully.');
            return response()->json([
                'status' => true,
                'message'=>'Category Updated succesfully.',
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
    public function destroy(string $id, Request $request){
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                'status'=>false,
                'message'=>'Category not found.'
            ]);
        }
        if($category){
            $oldimage = $category->image;
            $category->delete();
            File::delete(public_path().'/uploads/category/'.$oldimage);
            $request->session()->flash('success','Category Deleted succesfully.');
            return redirect()->route('categories.index');    
        }
    }
}
