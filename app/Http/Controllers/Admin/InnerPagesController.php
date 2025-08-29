<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\InnerPages;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Str;
use App\Models\Languages;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

class InnerPagesController extends Controller{

    private static $InnerPages;
    private static $TokenHelper;

    public function __construct(){
        self::$InnerPages = new InnerPages();
		self::$TokenHelper = new TokenHelper();
    }
	
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        return view('/panel/inner-pages/index');
    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }

        $query = self::$InnerPages->where('status', '!=', 3);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }
        $records = $query->orderBy('title', 'DESC')->paginate(20);
        return view('/panel/inner-pages/paginate', compact('records'));

    }

    #editPage
    public function editPage(Request $request,$row_id){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        if($request->input()){
           $validator = Validator::make($request->all(), [
				'title' => 'required',
				'description' => 'required',
			], [
				'title.required' => 'Please enter page title.',
				'description.required' => 'Please enter page description.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;
                }
				if($errors->first('description')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('description')));die;
                }
            } else {
				# profile pic upload
				if(isset($request->banner) && $request->banner->extension() != ""){
					$validator = Validator::make($request->all(), [
						'banner' => 'required|image|mimes:jpeg,png,jpg|max:2048'
					]);
					if($validator->fails()){
						$errors = $validator->errors();
						return json_encode(array('heading'=>'Error','msg'=>$errors->first('banner')));die;
					}else{
						$actual_image_name = time().'.'.$request->banner->extension();
						$destination = base_path().'/public/img/innerpages/';
						$request->banner->move($destination, $actual_image_name);
						if($request->input('old_banner') != ""){
							if(file_exists($destination.$request->input('old_banner'))){
								unlink($destination.$request->input('old_banner'));
							}
						}
					}
				}else{
					$actual_image_name = $request->input('old_banner');
				}
				
				$setData['title'] = $request->input('title');
				$setData['description'] = $request->input('description');
				$setData['seo_title'] = $request->input('seo_title');
				$setData['seo_description'] = $request->input('seo_description');
				$setData['seo_keyword'] = $request->input('seo_keyword');
				$setData['robot_tags'] = 'index,follow';
				$setData['image'] = $actual_image_name;
				$record = self::$InnerPages->where('id',$row_id)->update($setData);
				echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
            }
        }
		
		if($row_id > 0){
			$record = self::$InnerPages->where('id',$row_id)->first();
			return view('/panel/inner-pages/edit-page',compact(['record']));
		}else{
			return redirect('/panel/hotels');
		}
    }

}