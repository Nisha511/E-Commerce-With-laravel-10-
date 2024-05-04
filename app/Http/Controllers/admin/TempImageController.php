<?php

namespace App\Http\Controllers\admin;
use App\Models\TempImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;

class TempImageController extends Controller
{
    public function create(Request $request){
        $image = $request->image;
        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $new_name = time().'.'.$ext;
            // echo $new_name;exit;
            $tempImage = new TempImage();
            $tempImage->name = $new_name;
            $tempImage->save();

            $image->move(public_path().'/temp',$new_name);

            // $sPath = public_path().'/temp/'.$new_name;
            // $dPath = public_path().'/temp/thumb/'.$new_name;
            // $image = Image::make($sPath);
            // $image->fit(300,250);
            // $image->save($dPath);

            return response()->json([
                'status'=>true,
                'image_id'=>$tempImage->id,
                'Image_path'=>asset('temp/' . $new_name),
                'message'=>'Image Uploaded Succesfully'
            ]);
        }
    }
}
