<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Facilities;
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

class FacilitiesController extends Controller{
	
    private static $Facilities;
    private static $TokenHelper;
    public function __construct(){
        self::$Facilities = new Facilities();
		self::$TokenHelper = new TokenHelper();
    }
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        return view('/panel/facilities/index');
    }
    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $query = self::$Facilities->where('status', '!=', 3);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }
        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/facilities/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'title' => 'required', 
			], [
				'title.required' => 'Please enter title.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));
                    die;
                }
            } else {
				$setData['title'] = $request->input('title');

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
						$destination = base_path().'/public/img/facilities/';
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
					self::$Facilities->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
                    $setData['status'] = 1;
					$record = self::$Facilities->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }
        }
        return view('/panel/facilities/add-page');
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
			if($errors->first('title')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('rowId')));
				die;
			}
		}else{
			$record = self::$Facilities->where('id',$request->rowId)->first();
			echo json_encode(array('heading' => 'Success', 'record' => $record));die;
		}
	}
}