<?php

namespace App\Http\Controllers;

use App\Models\admin\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    //
    public function index(Request $request, $cat_slug = null, $sub_cat = null){
        $cat_sel = '';
        $subCat_sel = '';
        $brandArr = [];
        
        // dd($brandArr);
        $categories = Category::orderBy('name','ASC')
                                ->with('sub_category')
                                ->where('status',1)
                                ->get();
        
        $brands = Brand::orderBy('name','ASC')
                        ->where('status',1)
                        ->get();
        
        $products = Product::where('status',1);
        
        //Apply filter here
        if(!empty($cat_slug)){
            $category = Category::where('slug',$cat_slug)->first();
            $products = $products->where('category_id',$category->id);
            $cat_sel = $category->id;
        }
        if(!empty($sub_cat)){
            $sub_category = SubCategory::where('slug',$sub_cat)->first();
            $products = $products->where('sub_category_id',$sub_category->id);
            $subCat_sel = $sub_category->id;
        }
        if(!empty($request->get('brand'))){
            $brandArr = explode(',',$request->get('brand'));
            $products = $products->whereIn('brand_id',$brandArr);
        }
        if($request->get('price_min') != ''  && $request->get('price_max') != ''){
            if(intval($request->get('price_max')) === 1000){
                $products = $products->whereBetween('price',[intval($request->get('price_min')),1000000]);
            }else{
                $products = $products->whereBetween('price',[intval($request->get('price_min')),intval($request->get('price_max'))]);
            } 
        }
        if(!empty($request->get('search'))){
            $products = $products->where('name','LIKE','%'.$request->get('search').'%');
        }
        if($request->get('sort') != ''){
            if($request->get('sort')=='latest'){
                $products = $products->orderBy('id','DESC');
            }else if($request->get('sort')=='price_asc'){
                $products = $products->orderBy('price','ASC');
            }else if($request->get('sort')=='price_desc'){
                $products = $products->orderBy('price','DESC');
            }
        }else{
            $products = $products->orderBy('name','ASC');
        }
        // $products = $products->with('product_images');
        $products = $products->paginate(6);

        $data['categories'] = $categories;
        // dd($data['categories']);
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySel'] = $cat_sel;
        $data['subCategorySel'] = $subCat_sel;
        $data['brandSel'] = $brandArr;
        $data['priceMin'] = intval($request->get('price_min'));
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 1000 : intval($request->get('price_max'));
        $data['sort'] = $request->get('sort');
        // dd($data);
        return view('front.shop',$data);
    }
    public function product($slug){
        // echo $slug;
        $product = Product::where('slug',$slug)
                            ->withCount('product_rating')
                            ->withSum('product_rating','rating')
                            ->with('product_images','product_rating')->first();
        // dd($product);
        $avgRating = '0.00';
        $avgRatingPer = 0;
        if($product->product_rating_count > 0){
            $avgRating = number_format(($product->product_rating_sum_rating/$product->product_rating_count),2);
            $avgRatingPer = ($avgRating*100)/5;
        }
        $data['avgRating'] = $avgRating;
        $data['avgRatingPer'] = $avgRatingPer;
        if($product == null){
            abort(404);
        }else{
            $data['product'] = $product;
            return view('front.product',$data);
        }
    }
    public function productRating(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'rating' => 'required',
            'comment' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }else{
            $count = ProductRating::where('email',$request->email)
                                    ->where('product_id',$id)
                                    ->count();
            if($count > 0){
                session()->flash('error','You already give rating this product');
                return response()->json([
                    'status' => true
                ]);
            }else{
                $rating = new ProductRating();
                $rating->product_id = $id;
                $rating->username = $request->name;
                $rating->email = $request->email;
                $rating->comment = $request->comment;
                $rating->rating = $request->rating;
                $rating->save();
                session()->flash('success','Product rating has fiven successfully.');
                return response()->json([
                    'status' => true,
                    'message' => 'Product rating has fiven successfully.'
                ]);
            }
        }
    }
}
