<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    //
    
    public function index(){
        $products = Product::orderBy('name')
                            ->with('product_images')
                            ->where('is_featured','Yes')
                            ->where('status',1)
                            ->take(8)
                            ->get();
        // dd($products);
        $data['products'] = $products;

        $latestProducts = Product::orderBy('id','DESC')
                            ->with('product_images')
                            ->where('is_featured','Yes')
                            ->where('status',1)
                            ->take(8)
                            ->get();
        // dd($products);
        $data['latestProducts'] = $latestProducts;
        return view('front.home',$data);
    }

    public function addToWishlist(Request $request){
        if(Auth::check() == false){
            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false
            ]);
        }
        $product = Product::where('id',$request->id)->first();
        if($product == null){
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product Not Found</div>',
            ]);
        }
        // $wishlist = new Wishlist();
        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();
        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ]
        );
        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->name.'"</strong> added into your WishList</div>',
        ]);
    }
}
