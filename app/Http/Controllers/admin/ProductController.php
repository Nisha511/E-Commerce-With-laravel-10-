<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // ->join('orders', 'users.id', '=', 'orders.user_id')
    public function index(Request $request){
        // DB::enableQueryLog();
        // $products = Product::select('pr.*', 'cat.name', 'sc.name', 'br.name')
        // ->from('products as pr')
        // ->join('categories as cat','pr.category_id','=','cat.id')
        // ->join('sub_categories as sc','pr.sub_category_id','=', 'sc.id')
        // ->join('brands as br','pr.brand_id','=','br.id')
        // ->get();
        // // dd($products);
        // dd(DB::getQueryLog());
       
        $products = Product::with('category')
                 ->with('subcategory')
                 ->with('brand')
                 ->with('product_images')
                ->latest('id');
        if(!empty($request->get('table_search'))){
            $products = $products->where('name','like','%'.$request->get('table_search').'%');
        }
        $products = $products->paginate(10);
            // dd($products);
        // echo "<PRE>";print_R($products);die;
        return view('admin.products.list',compact('products'));
        // $categories = Category::all();
        // $sub_categories = SubCategory::all();
        // $brands = Brand::all();
    }
    public function create(){
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        return view('admin.products.create',compact('categories','brands'));
    }
    public function get_sub_category(Request $request){
        $sub_categories = SubCategory::where('category_id','=',$request->category_id)->get();

        echo json_encode($sub_categories);
    }
    public function store(Request $request){
        $imageIds =  $request->image_id;
        // dd($imageIds);
        if (!is_array($imageIds)) {
            $imageIds = explode(',', $imageIds);
        }
        // if (!empty($imageIds)) {
        //     foreach ($imageIds as $imageId) {
        //         dd("Processing Image ID: $imageId");
        //         // ... rest of your code ...
        //     }
        // }
        // exit;
        // echo "<PRE>";print_r($imageIds);exit;
        // dd($imageIds);
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No'
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
           $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            $product = new Product();
            $product->name = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();
            // dd($product);
            if(!empty($imageIds)){
                foreach ($imageIds as $imageId) {
                    $tempImage = TempImage::find($imageId);
                    if($tempImage){
                        $extArray = explode('.',$tempImage->name);
                        $ext = last($extArray);
                        $new_image_name = $product->id.'.'.$ext;
                        $sPath = public_path().'/temp/'.$tempImage->name;
                        $dPath = public_path().'/uploads/products/'.$new_image_name;
                        File::copy($sPath,$dPath);
                        $image_product = new ProductImage();
                        $image_product->product_id = $product->id;
                        $image_product->image = $new_image_name;
                        $image_product->save();
                    }
                }
            }
            $request->session()->flash('success','Product Insert Successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Product Insert Successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit(string $id, Request $request){
        $product = Product::with('product_images')->find($id);
        if(!$product){
            $request->session()->flash('error','Product Not Found.');
        }
        // echo "<PRE>";print_r($product);exit;
        $categories = Category::orderby('name')->get();
        // dd($categories);
        $subCategories = SubCategory::all();
        $brands = Brand::orderBy('name')->get();
        // dd($brands);
        return view('admin.products.edit',compact('product','categories','brands','subCategories'));
    }
    public function update(string $id, Request $request){
        $product = Product::with('product_images')->find($id);
        if(!$product){
            return redirect()->route('products.index')->with('error','Product Not Found.');
        }
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id,
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No'
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
           $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            $product->name = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();
            
            if (!empty($request->image_id)) {
                $oldImages = $product->product_images;
                // echo "<PRE>";print_r($oldImages);exit;
                foreach ($oldImages as $oldImage) {
                    $oldImagePath = public_path('uploads/products/' . $oldImage->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    $oldImage->delete();
                }
                foreach ($request->image_id as $imageId) {
                    $tempImage = TempImage::find($imageId);
                    if ($tempImage) {
                        $extArray = explode('.', $tempImage->name);
                        $ext = last($extArray);
                        $new_image_name = $product->id . '.' . $ext;
                        $sPath = public_path('/temp/' . $tempImage->name);
                        $dPath = public_path('/uploads/products/' . $new_image_name);
                        File::copy($sPath, $dPath);
        
                        $image_product = new ProductImage();
                        $image_product->product_id = $product->id;
                        $image_product->image = $new_image_name;
                        $image_product->save();
                    }
                }
            }
        
            $request->session()->flash('success','Product update Successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Product Update Successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy(Request $request, string $id){
        $product = Product::find($id);
        if(empty($product)){
            return response()->json([
                'status'=>false,
                'message'=>'Product Not Found'
            ]);
        }
        if($product){
            $product_imgs = ProductImage::where('product_id', $id)->get();
            foreach ($product_imgs as $product_img) {
                $pd_img = $product_img->image;
                if ($pd_img) {
                    File::delete(public_path() . '/uploads/products/' . $pd_img);
                }
            }
            $product->delete();
            $request->session()->flash('success','Product Deleted succesfully.');
            return redirect()->route('products.index'); 
        }
    }
    // public function getProducts(Request $request){
        // $productArr = [];
        // if($request->term != ""){
        //     $products = Product::where('name','like','%'.$request->term.'%')->get();
        //     if($products != null){
        //         foreach($products as $product){
        //             $productArr[] = array('id'=>$product->id,'title'=>$product->name);
        //         }
        //     }
        // }
    //     // dd($products);
        
    //     // echo "<PRE>";print_r($productArr);
    //     return response()->json([
    //         'status'=>true,
    //         'tags'=>$productArr
    //     ]);
    // }
    
    public function productRatings(){
        $data['product_ratings'] = ProductRating::select('product_ratings.*','products.name as title')
                                        ->leftJoin('products','product_ratings.product_id','=', 'products.id')
                                        ->orderBy('id','ASC')
                                        ->paginate(10);
        // dd($product_ratings);
        return view('admin.products.rating',$data);
    }
    public function changeProductRatings(Request $request){
        $rating = ProductRating::find($request->id);
        $rating->status = $request->status;
        $rating->save();
        
        session()->flash('success','Product rating status changed successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product rating status changed successfully'
        ]);
    }
    
}
