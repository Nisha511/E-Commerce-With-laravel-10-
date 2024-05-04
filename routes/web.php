<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;        
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test', function () {
//     orderEmail(13);
// });

Route::get('/',[FrontController::class,'index'])->name('home');
Route::get('/shop/{catSlug?}/{subCatSlug?}',[ShopController::class,'index'])->name('front-home');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('front-product');
Route::get('/cart',[CartController::class,'cart'])->name('front.cart');
Route::post('/addToCart',[CartController::class,'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::get('/cart-summary',[CartController::class,'getCartSummary'])->name('front.getCartSummary');
Route::post('/delete-cart',[CartController::class,'deleteCart'])->name('front.deleteCart');
Route::get('/checkout',[CartController::class,'checkout'])->name('front.checkout');
Route::post('/process-checkout',[CartController::class,'processCheckout'])->name('front.processCheckout');
Route::post('/get-country-chanrge',[CartController::class,'getCountryWiseCharge'])->name('front.getCountryWiseCharge');
Route::get('/thanks/{order_id}',[CartController::class,'thankyou'])->name('front.thanks');
Route::get('/cancel',[CartController::class,'paymentCancel'])->name('stripe.cancel');
Route::post('/apply_discount',[CartController::class,'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove_discount',[CartController::class,'removeDiscount'])->name('front.removeDiscount');
Route::post('/add-to-cart',[FrontController::class,'addToWishlist'])->name('front.addToWishlist');
Route::post('/product-rating/{product_id}',[ShopController::class,'productRating'])->name('front.productRating');
Route::get('/success',[CartController::class,'success'])->name('success');
Route::get('/cancel',[CartController::class,'cancel'])->name('cancel');
Route::post('/stripe/checkout', [StripeController::class, 'processPayment'])->name('stripe.checkout');


Route::group(['prefix' => 'account'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::get('/register', [AuthController::class, 'register'])->name('account.register');
        Route::post('/register-store',[AuthController::class,'store'])->name('register.store');
        Route::get('/login', [AuthController::class, 'login'])->name('account.login');
        Route::post('/submit-login', [AuthController::class, 'login_store'])->name('login.store');
        Route::get('/forgot-password',[AuthController::class,'forgotPasswordForm'])->name('account.forgotPasswordForm');
        Route::post('/process-forgot-password', [AuthController::class, 'processForgotPassword'])->name('account.processForgotPassword');
        Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('account.resetPassword');
        Route::post('/process-reset-password/{token}', [AuthController::class, 'processResetPassword'])->name('account.processResetPassword');
    });

    Route::group(['middleware'=>'auth'], function(){
        Route::get('/profile',[AuthController::class,'profile'])->name('account.profile');
        Route::post('/update-profile',[AuthController::class,'updateProfile'])->name('account.updateProfile');
        Route::post('/update-address',[AuthController::class,'updateAddress'])->name('account.updateAddress');
        Route::get('/logout',[AuthController::class,'logout'])->name('account.logout');
        Route::get('/order', [AuthController::class, 'order'])->name('account.order');
        Route::get('/order-detail/{id}', [AuthController::class, 'orderDetail'])->name('account.orderDetail');
        Route::get('/wishlist', [AuthController::class, 'wishList'])->name('account.wishList');
        Route::post('/remove-product-from-wishlist', [AuthController::class, 'RemoveProductFromWishlist'])->name('account.RemoveProductFromWishlist');
        Route::get('/change-password',[AuthController::class,'changePassword'])->name('account.changePassword');
        Route::post('/update-password',[AuthController::class,'updatePassword'])->name('account.updatePassword');
    });
});

Route::group(['prefix'=>'admin'],function(){
    Route::group(['middleware'=>'admin.guest'], function(){
        Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware'=>'admin.auth'], function(){
        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');
        Route::get('/admin-change-password',[HomeController::class,'changePassword'])->name('admin.changePassword');
        Route::post('/admin-update-password',[HomeController::class,'updatePassword'])->name('admin.updatePassword');
        //categories
        Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
        Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');
        Route::get('/categories/edit/{id}',[CategoryController::class,'edit'])->name('categories.edit');
        Route::post('/categories/{id}',[CategoryController::class,'update'])->name('categories.update');
        Route::get('/categories/destroy/{id}',[CategoryController::class,'destroy'])->name('categories.destroy');

        //Sub Category
        Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
        Route::post('/sub-categories',[SubCategoryController::class,'store'])->name('sub-categories.store');
        Route::get('/sub-categories/edit/{id}',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
        Route::post('/sub-categories/{id}',[SubCategoryController::class,'update'])->name('sub-categories.update');
        Route::get('/sub-categories/destroy/{id}',[SubCategoryController::class,'destroy'])->name('sub-categories.destroy');

        //Brand
        Route::get('/brands',[BrandController::class,'index'])->name('brands.index');
        Route::get('/brands/create',[BrandController::class,'create'])->name('brands.create');
        Route::post('/brands',[BrandController::class,'store'])->name('brands.store');
        Route::get('/brands/edit/{id}',[BrandController::class,'edit'])->name('brands.edit');
        Route::post('/brands/{id}',[BrandController::class,'update'])->name('brands.update');
        Route::get('/brands/destroy/{id}',[BrandController::class,'destroy'])->name('brands.destroy');

        //products
        Route::get('/products',[ProductController::class,'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
        Route::post('/products',[ProductController::class,'store'])->name('products.store');
        Route::get('/products/edit/{id}',[ProductController::class,'edit'])->name('products.edit');
        Route::post('/products/{id}',[ProductController::class,'update'])->name('products.update');
        Route::get('/products/destroy/{id}',[ProductController::class,'destroy'])->name('products.destroy');
        Route::get('/products/get_sub_category',[ProductController::class,'get_sub_category'])->name('products.get_sub_category');
        Route::get('products/getProduct',[ProductController::class,'getProducts'])->name('products.getProducts');
        Route::get('/product-ratings',[ProductController::class,'productRatings'])->name('products.productRatings');
        Route::get('/change-rating',[ProductController::class,'changeProductRatings'])->name('products.changeProductRatings');

        //shipping management
        Route::get('/shipping',[ShippingController::class,'index'])->name('shipping.index');
        Route::get('/shipping/create',[ShippingController::class,'create'])->name('shipping.create');
        Route::post('/shipping',[ShippingController::class,'store'])->name('shipping.store');
        Route::get('/shipping/edit/{id}',[ShippingController::class,'edit'])->name('shipping.edit');
        Route::post('/shipping/{id}',[ShippingController::class,'update'])->name('shipping.update');
        Route::get('/shipping/destroy/{id}',[ShippingController::class,'destroy'])->name('shipping.destroy');

        //discount section for admin
        Route::get('/discount-coupon',[DiscountCodeController::class,'index'])->name('discount-coupon.index');
        Route::get('/discount-coupon/create',[DiscountCodeController::class,'create'])->name('discount-coupon.create');
        Route::post('/discount-coupon',[DiscountCodeController::class,'store'])->name('discount-coupon.store');
        Route::get('/discount-coupon/edit/{id}',[DiscountCodeController::class,'edit'])->name('discount-coupon.edit');
        Route::post('/discount-coupon/{id}',[DiscountCodeController::class,'update'])->name('discount-coupon.update');
        Route::get('/discount-coupon/destroy/{id}',[DiscountCodeController::class,'destroy'])->name('discount-coupon.destroy');

        //order
        Route::get('/adminOrder',[OrderController::class,'index'])->name('adminOrder.index');
        Route::get('/adminOrder-detail/{id}',[OrderController::class,'detail'])->name('adminOrder-detail.detail');
        Route::post('/update-order-status/{id}',[OrderController::class,'updateOrdeStatus'])->name('admin-order.updateOrdeStatus');
        Route::post('/send-invoice-mail/{id}',[OrderController::class,'sendInvoiceMessage'])->name('admin-order.sendInvoiceMessage');
        
        //user
        Route::get('/user',[UserController::class,'index'])->name('user.index');
        Route::get('/user/create',[UserController::class,'create'])->name('user.create');
        Route::post('/user',[UserController::class,'store'])->name('user.store');
        Route::get('/user/edit/{id}',[UserController::class,'edit'])->name('user.edit');
        Route::post('/user/{id}',[UserController::class,'update'])->name('user.update');
        Route::get('/user/destroy/{id}',[UserController::class,'destroy'])->name('user.destroy');

        //pages
        Route::get('/page',[PageController::class,'index'])->name('page.index');
        Route::get('/page/create',[PageController::class,'create'])->name('page.create');
        Route::post('/page',[PageController::class,'store'])->name('page.store');
        Route::get('/page/edit/{id}',[PageController::class,'edit'])->name('page.edit');
        Route::post('/page/{id}',[PageController::class,'update'])->name('page.update');
        Route::get('/page/destroy/{id}',[PageController::class,'destroy'])->name('page.destroy');

        Route::post('/uploadImage',[TempImageController::class,'create'])->name('temp-images.create');
        Route::get('/getSlug', function(Request $request){
            $slug = '';
            if(!empty($request->title)){
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug,
            ]);
        })->name('getSlug');
    });
});