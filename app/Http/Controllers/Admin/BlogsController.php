<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Blogs;
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
class BlogsController extends Controller{
    private static $Blogs;
    private static $TokenHelper;
    public function __construct(){
        self::$Blogs = new Blogs();
    }
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        return view('/panel/blogs/index');
    }
    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        $query = self::$Blogs->where('status', '!=', 3);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }
		if($request->session()->get('admin_type') != 'Admin'){
			$query->where('added_by', $request->session()->get('admin_id'));
		}
        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/blogs/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'title' => 'required',
				'category' => 'required'
			], [
				'title.required' => 'Please enter title.',
				'category.required' => 'Please select category.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;
                }
				if($errors->first('category')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('category')));die;
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
						$destination = base_path().'/public/img/blogs/';
						$request->banner->move($destination, $actual_image_name);
						$setData['image'] = $actual_image_name;
					}
				}
				
				$setData['title'] = $request->input('title');
				$setData['slug'] = Str::slug($request->input('category').'-'.$request->input('title'));
				$setData['category'] = $request->input('category');
				$setData['short_description'] = $request->input('short_description');
				$setData['description'] = $request->input('description');
				$setData['blog_date'] = $request->input('blog_date');
				$setData['seo_title'] = $request->input('seo_title');
				$setData['seo_description'] = $request->input('seo_description');
				$setData['seo_keyword'] = $request->input('seo_keyword');
				$setData['robot_tags'] = 'index,follow';
				$record = self::$Blogs->CreateRecord($setData);

				echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
            }
        }
		
        return view('/panel/blogs/add-page');
    }
	#editPage
    public function updateBlogstatus(Request $request){
		$setData['popular_post'] = $request->status;
		self::$Blogs->where('id',$request->row_id)->update($setData);
		echo 'Success'; die;
	}
	#editPage
    public function editPage(Request $request,$row_id){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        if($request->input()){
           $validator = Validator::make($request->all(), [
				'title' => 'required',
				'category' => 'required',
			], [
				'title.required' => 'Please enter title.',
				'category.required' => 'Please select category.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;
                }
				if($errors->first('category')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('category')));die;
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
						$destination = base_path().'/public/img/blogs/';
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
				$setData['slug'] = Str::slug($request->input('category').'-'.$request->input('title'));
				$setData['category'] = $request->input('category');
				$setData['short_description'] = $request->input('short_description');
				$setData['description'] = $request->input('description');
				$setData['blog_date'] = $request->input('blog_date');
				$setData['seo_title'] = $request->input('seo_title');
				$setData['seo_description'] = $request->input('seo_description');
				$setData['seo_keyword'] = $request->input('seo_keyword');
				$setData['robot_tags'] = 'index,follow';
				$record = self::$Blogs->where('id',$row_id)->update($setData);				
				echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
            }
        }
		
		if($row_id > 0){
			$record = self::$Blogs->where('id',$row_id)->first();
			return view('/panel/blogs/edit-page',compact(['record']));
		}else{
			return redirect('/panel/blogs');
		}
    }
}