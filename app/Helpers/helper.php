<?php
use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Order;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Mail;
function getcategories(){
    $categories = Category::orderBy('name')
                            ->with('sub_category')
                            ->where('status',1)
                            ->where('showHome','Yes')
                            ->get();
    // echo "<PRE>";print_r($categories);exit;
    // dd($categories); 
    return $categories;
}
function getProductImage($productId){
    return ProductImage::where('product_id',$productId)->first();
}

function orderEmail($order_id, $user='customer'){
    $order = Order::where('id',$order_id)->with('items')->first();
    if($user == 'customer'){
        $subject = 'Thanks for Your Order';
        $email = $order->email;
    }else{
        $subject = 'You have receive order';
        $email = env('ADMIN_EMAIL');
    }
    
    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'user_type' => $user
    ];
    Mail::to($email)->send(new OrderEmail($mailData));
}

function getCountryName($id){
    $country = Country::where('id',$id)->first();
    return $country ? $country->name : '';
}

?>