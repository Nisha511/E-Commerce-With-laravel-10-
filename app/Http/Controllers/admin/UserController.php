<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function index(Request $request){
        $users = User::latest();
        if(!empty($request->get('table_search'))){
            $users = $users->where('name','LIKE','%'.$request->get('table_search').'%');
            $users = $users->orWhere('email','LIKE','%'.$request->get('table_search').'%');
        }
        $users->where('id','!=',1);
        $users = $users->paginate(5);
        $data['users'] = $users;
        return view('admin.users.list',$data);
    }
    public function create(){
        return view('admin.users.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone' => 'required'
        ]);

        if($validator->passes()){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->status = $request->status;
            $user->save();
            if($user){
                session()->flash('success','User Create Successfully.');
                return response()->json([
                    'status' => true,
                    'message' => 'user create successfully'
                ]);
            }else{
                session()->flash('error','problem while creating user.');
                return response()->json([
                    'status' => true,
                    'message' => 'problem while creating user'
                ]);
            }
            
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit(Request $request, $id){
        $user = User::find($id);
        if($user == null){
            // session()->flash('error','User Not Found');
            return redirect()->route('user.index')->with('error','User Not Found');
        }
        $data['user'] = $user;
        return view('admin.users.edit', $data);
    }
    public function update(Request $request, $id){
        $user = User::find($id);
        if($user == null){
            // session()->flash('error','User Not Found');
            return redirect()->route('user.index')->with('error','User Not Found');
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required'
        ]);

        if($validator->passes()){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;
            if(!empty($request->password)){
                $user->password = Hash::make($request->password);
            }
            $user->save();
            if($user){
                session()->flash('success','User Create Successfully.');
                return response()->json([
                    'status' => true,
                    'message' => 'user create successfully'
                ]);
            }else{
                session()->flash('error','problem while creating user.');
                return response()->json([
                    'status' => true,
                    'message' => 'problem while creating user'
                ]);
            }
            
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy(Request $request, $id){
        $user = User::find($id);
        if(!$user){
            // return response()->json([
            //     'status'=>false,
            //     'message'=>'user not found.'
            // ]);
            return redirect()->route('user.index')->with('error','User Not Found');
        }
        if($user){
            $user->delete();
            $request->session()->flash('success','user Deleted succesfully.');
            return redirect()->route('user.index');    
        }
    }
}
