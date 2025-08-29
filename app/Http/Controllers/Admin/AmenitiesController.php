<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Amenities;
use App\Models\AmenityCategories;
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

class AmenitiesController extends Controller{
	
    private static $Amenities;
    private static $TokenHelper;
    private static $AmenityCategories;
    public function __construct(){
        self::$Amenities = new Amenities();
		self::$TokenHelper = new TokenHelper();
		self::$AmenityCategories = new AmenityCategories();
    }
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $categories = self::$AmenityCategories->where('status',1)->get();
        return view('/panel/amenities/index', compact('categories'));
    }
    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $query = self::$Amenities->join('amenity_categories','amenity_categories.id','=','amenities.category_id')->where('amenities.status', '!=', 3)
        ->select(['amenities.*', 'amenity_categories.title as category_title']);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('amenities.title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('amenities.status', $request->input('search_status'));
        }
        if($request->input('search_category_id') && $request->input('search_category_id') != ""){
            $query->where('amenities.category_id', $request->input('search_category_id'));
        }
        $records = $query->orderBy('amenities.id', 'DESC')->paginate(20);
        return view('/panel/amenities/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'category_id' => 'required', 
				'title' => 'required', 
			], [
                'category_id.required' => 'Please select category.',
				'title.required' => 'Please enter title.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('category_id')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('category_id')));
                    die;
                }
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));
                    die;
                }
            } else {
				$setData['title'] = $request->input('title');
                $setData['category_id'] = $request->input('category_id');

                # profile pic upload
				if(isset($request->icon) && $request->icon->extension() != ""){
					$validator = Validator::make($request->all(), [
						'icon' => 'required|image|mimes:jpeg,png,jpg|max:1048'
					]);
					if($validator->fails()){
						$errors = $validator->errors();
						return json_encode(array('heading'=>'Error','msg'=>$errors->first('banner')));die;
					}else{
						$actual_image_name = time().'.'.$request->icon->extension();
						$destination = base_path().'/public/img/amenities/';
						$request->icon->move($destination, $actual_image_name);
                        $setData['icon'] = $actual_image_name;

						if($request->input('old_icon') != "" && $request->input('row_id') > 0){
							if(file_exists($destination.$request->input('old_icon'))){
								unlink($destination.$request->input('old_icon'));
							}
						}
					}
				}else{
                    if($request->input('row_id') > 0){
					    $actual_image_name = $request->input('old_icon');
                    }
				}

				if($request->input('row_id') > 0){
					self::$Amenities->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
                    $setData['status'] = 1;
					$record = self::$Amenities->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }
        }
        return view('/panel/amenities/add-page');
    }
    #getPage
    public function getPage(Request $request){
		$validator = Validator::make($request->all(), [
			'rowId' => 'required|numeric', 
		], [
			'rowId.required' => 'Please enter rowId.'
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('rowId')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('rowId')));
				die;
			}
		}else{
			$record = self::$Amenities->where('id',$request->rowId)->first();
            $record->icon_image = "";
            if(!empty($record->icon)){
                $record->icon_image = getenv('APP_URL').'/public/img/amenities/'.$record->icon;
            }
			echo json_encode(array('heading' => 'Success', 'record' => $record));die;
		}
	}
}