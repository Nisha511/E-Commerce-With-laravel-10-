<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function index(Request $request){
        $orders = Order::latest();
        if(!empty($request->get('table_search'))){
            $orders = $orders->where('first_name','like','%'.$request->get('table_search').'%');
            $orders = $orders->orWhere('last_name','like','%'.$request->get('table_search').'%');
            $orders = $orders->orWhere('email','like','%'.$request->get('table_search').'%');
            $orders = $orders->orWhere('status','like','%'.$request->get('table_search').'%');
        }
        $orders = $orders->orderBy('created_at','DESC');
        $orders = $orders->paginate(6);
        return view('admin.order.list',[
            'orders' => $orders
        ]);
    }
    public function detail(string $id){
        $order_detail = Order::select('orders.*', 'countries.name as country_name')
                                ->leftJoin('countries', 'orders.country_id', '=', 'countries.id')
                                ->where('orders.id', $id)
                                ->first(); 
        $orderItem = OrderItem::where('order_id',$id)->get();
        return view('admin.order.detail',[
            'order_detail' => $order_detail,
            'orderItem' => $orderItem
        ]);
    }
    public function updateOrdeStatus(Request $request, string $id){
        $order = Order::find($id);
        $order->status = $request->status;
        $order->shipped_date = $request->Sdate;
        $order->save();
        if($order){
            return response()->json([
                'status' => 'Success',
                'message' => 'Order Status update Successfully.',
                'order_status' => ucwords($order->status)
            ]);
        }else{
            return response()->json([
                'status' => 'Error',
                'message' => 'Error while updating order status.'
            ]);
        }
    }

    public function sendInvoiceMessage(Request $request, $id){
        // echo $id;
        orderEmail($id,$request->userType);
        $message = 'Order email sent succesfully';
        session()->flash('success',$message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
