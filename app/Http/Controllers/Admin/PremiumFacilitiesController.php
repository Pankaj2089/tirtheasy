<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PremiumFacilities;
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

class PremiumFacilitiesController extends Controller{
	
    private static $PremiumFacilities;
    private static $TokenHelper;
    public function __construct(){
        self::$PremiumFacilities = new PremiumFacilities();
		self::$TokenHelper = new TokenHelper();
    }
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        return view('/panel/premium-facilities/index');
    }
    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $query = self::$PremiumFacilities->where('status', '!=', 3);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }
        if($request->input('facility_type') && $request->input('facility_type') != ""){
            $query->where('facility_type', $request->input('facility_type'));
        }
        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/premium-facilities/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'title' => 'required', 
                'icon' => 'required', 
                'facility_type' => 'required', 
                'description' => 'required', 
			], [
				'title.required' => 'Please enter title.',
				'facility_type.required' => 'Please select facility type.',
				'icon.required' => 'Please enter icon.',
				'description.required' => 'Please enter description.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));
                    die;
                }
                if($errors->first('facility_type')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('facility_type')));
                    die;
                }
                if($errors->first('icon')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('icon')));
                    die;
                }
                if($errors->first('description')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('description')));
                    die;
                }
            } else {
				$setData['title'] = $request->input('title');
				$setData['icon'] = $request->input('icon');
				$setData['description'] = $request->input('description');
				$setData['facility_type'] = $request->input('facility_type');
				if($request->input('row_id') > 0){
					self::$PremiumFacilities->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
                    $setData['status'] = 1;
					$record = self::$PremiumFacilities->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
            }
        }
        return view('/panel/premium-facilities/add-page');
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
			$record = self::$PremiumFacilities->where('id',$request->rowId)->first();
			echo json_encode(array('heading' => 'Success', 'record' => $record));die;
		}
	}
}