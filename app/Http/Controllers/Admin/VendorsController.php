<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
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

class VendorsController extends Controller{

    private static $AdminUser;
    private static $TokenHelper;

    public function __construct(){
        self::$AdminUser = new AdminUser();
		self::$TokenHelper = new TokenHelper();
    }
	
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        return view('/panel/vendors/index');
    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }

        $query = self::$AdminUser->where('status', '!=', 3)->where('type', 'Vendor');
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('name', 'like', '%' . $request->input('search_title') . '%')
            ->orWhere('email', 'like', '%' . $request->input('search_title') . '%')
            ->orWhere('mobile', 'like', '%' . $request->input('search_title') . '%')
            ->orWhere('property_id', 'like', '%' . $request->input('property_id') . '%')
            ->orWhere('login_id', 'like', '%' . $request->input('login_id') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }
        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/vendors/paginate', compact('records'));

    }

    #add new Service Type
    public function addPage(Request $request){

        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        if($request->input()){

            $validator = Validator::make($request->all(), [
                
				'login_id' => 'required',
				'name' => 'required', 
				'email' => 'required',  
				'mobile' => 'required|numeric', 
				'password' => 'required', 
			],[
				'login_id.required' => 'Please enter login user name.',
                'name.required' => 'Please enter name.',
				'email.required' => 'Please enter email.',
				'mobile.required' => 'Please enter mobile.',
				'password.required' => 'Please enter password.'
			]);

            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('login_id')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('login_id'))); die;}
                if($errors->first('name')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('name'))); die;}
                if($errors->first('password')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('password'))); die;}
				if($errors->first('email')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('email'))); die;}
				if($errors->first('mobile')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('mobile'))); die;}
            }else{

				$setData['type'] = 'Vendor';
				$setData['name'] = $request->input('name');
				$setData['mobile'] = $request->input('mobile');
                $setData['login_id'] = $request->input('login_id');

				if($request->input('row_id') <= 0 && $request->input('password') != ''){
					$setData['password'] = password_hash($request->input('password'),PASSWORD_BCRYPT);
				}

				if($request->input('row_id') > 0){
					
                    $loginIDExist = self::$AdminUser->where('login_id',$request->login_id)->where('type',"Vendor")->where('id','!=',$request->row_id)->count();
					if($loginIDExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Login User ID already exist')); die;
					}
					$mobileExist = self::$AdminUser->where('mobile',$request->mobile)->where('type',"Vendor")->where('id','!=',$request->row_id)->count();
					if($mobileExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Mobile no already exist')); die;
					}
					$emailExist = self::$AdminUser->where('email',$request->email)->where('type',"Vendor")->where('id','!=',$request->row_id)->count();
					if($emailExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Email address already exist')); die;
					}
					
					self::$AdminUser->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{

                    $loginIDExist = self::$AdminUser->where('login_id',$request->login_id)->where('type',"Vendor")->count();
					if($loginIDExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Login User ID already exist')); die;
					}
					
					$mobileExist = self::$AdminUser->where('mobile',$request->mobile)->where('type',"Vendor")->count();
					if($mobileExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Mobile no already exist')); die;
					}
					$emailExist = self::$AdminUser->where('email',$request->email)->where('type',"Vendor")->count();
					if($emailExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Email address already exist')); die;
					}
					
					$record = self::$AdminUser->CreateRecord($setData);

                    $property_id = str_pad($record->id, 4, '0', STR_PAD_LEFT);
                    // Update the record with new property_id
                    $record->update(['property_id' => $property_id]);

					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
            }
        }

        return view('/panel/vendors/add-page');

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
			$record = self::$AdminUser->where('id',$request->rowId)->first();
			echo json_encode(array('heading' => 'Success', 'record' => $record));die;
		}
	}
}