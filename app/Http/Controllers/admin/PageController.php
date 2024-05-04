<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    //
    public function index(Request $request){
        $data = [];
        $pages = Page::latest();
        if(!empty($request->table_search)){
            $pages = $pages->where('name','LIKE','%'.$request->table_search.'%');
            $pages = $pages->orWhere('slug','LIKE','%'.$request->table_search.'%');
        }
        $pages = $pages->paginate(5);
        $data['pages'] = $pages;
        return view('admin.pages.list', $data);
    }
    public function create(){
        return view('admin.pages.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
        ]);
        if($validator->passes()){
            $page = new Page();
            $page->name = $request->name;
            $page->slug = $request->slug;
            if(!empty($request->content)){
                $page->content = $request->content;
            }
            $page->save();
            if($page){
                $request->session()->flash('success','Page created successfully.');
                return response()->json([
                    'status' => true,
                    'message' => 'Page create successfully.'
                ]);
            }else{
                session()->flash('error','Problem while saving page.');
                return response()->json([
                    'status' => true,
                    'message' => 'Problem while saving page.'
                ]);
            }
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id){
        $page = Page::find($id);
        return view('admin.pages.edit',[
            'page' => $page
        ]);
    }
    public function update(Request $request, $id){
        $page = Page::find($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
        ]);
        if($validator->passes()){
            $page->name = $request->name;
            $page->slug = $request->slug;
            if(!empty($request->content)){
                $page->content = $request->content;
            }
            $page->save();
            if($page){
                $request->session()->flash('success','Page edit successfully.');
                return response()->json([
                    'status' => true,
                    'message' => 'Page edit successfully.'
                ]);
            }else{
                session()->flash('error','Problem while editing page.');
                return response()->json([
                    'status' => true,
                    'message' => 'Problem while editing page.'
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
        $page = Page::find($id);
        if($page == null){
            $request->session()->flash('error','Page Not Found');
        }
        $page->delete();
        $request->session()->flash('success','Page Deleted');
        return redirect()->route('page.index');
    }
}
