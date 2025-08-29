<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\States;
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

class CitiesController extends Controller{
	
    private static $Cities;
    private static $TokenHelper;
    private static $States;
    public function __construct(){
        self::$Cities = new Cities();
		self::$States = new States();
		self::$TokenHelper = new TokenHelper();
    }
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $states = self::$States->where('status',1)->get();
        return view('/panel/cities/index', compact('states'));
    }
    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $query = self::$Cities->join('states','states.id','=','cities.state_id')->where('cities.status', '!=', 3)
        ->select(['cities.*', 'states.title as state_title']);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('cities.title', 'like', '%' . $request->input('search_title') . '%');
        }
        if($request->input('search_state_id') && $request->input('search_state_id') != ""){
            $query->where('cities.state_id', $request->input('search_state_id'));
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('cities.status', $request->input('search_status'));
        }
        $records = $query->orderBy('cities.id', 'DESC')->paginate(20);
        return view('/panel/cities/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'title' => 'required', 
                'state_id' => 'required', 
			], [
				'title.required' => 'Please enter title.',
				'state_id.required' => 'Please select state.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));
                    die;
                }
                if($errors->first('state_id')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('state_id')));
                    die;
                }
            } else {
				$setData['title'] = $request->input('title');
                $setData['country_id'] = 101;
                $setData['state_id'] = $request->input('state_id');
				if($request->input('row_id') > 0){
					self::$Cities->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
					$record = self::$Cities->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }
        }
        return view('/panel/cities/add-page');
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
			$record = self::$Cities->where('id',$request->rowId)->first();
			echo json_encode(array('heading' => 'Success', 'record' => $record));die;
		}
	}
}