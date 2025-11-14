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

    private static $UserModal;
    private static $TokenHelper;

    public function __construct(){
        self::$UserModal = new AdminUser();
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

        $query = self::$UserModal->where('status', '!=', 3)->where('type', 'Vendor');
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
			],[
				'login_id.required' => 'Please enter login user name.',
                'name.required' => 'Please enter name.',
				'email.required' => 'Please enter email.',
				'mobile.required' => 'Please enter mobile.',
			]);

            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('login_id')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('login_id'))); die;}
                if($errors->first('name')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('name'))); die;}
				if($errors->first('email')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('email'))); die;}
				if($errors->first('mobile')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('mobile'))); die;}
            }else{

				$setData['type'] = 'Vendor';
				$setData['name'] = $request->input('name');
				$setData['mobile'] = $request->input('mobile');
                $setData['login_id'] = $request->input('login_id');
                $setData['email'] = $request->input('email');

				if($request->input('row_id') <= 0){
					if(empty($request->input('password'))){return json_encode(array('heading' => 'Error', 'msg' => "Please enter vendor password")); die;}
					$setData['password'] = password_hash($request->input('password'),PASSWORD_BCRYPT);
				}

				if($request->input('row_id') > 0){
					
                    $loginIDExist = self::$UserModal->where('login_id',$request->login_id)->where('type',"Vendor")->where('id','!=',$request->row_id)->count();
					if($loginIDExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Login User ID already exist')); die;
					}
					$mobileExist = self::$UserModal->where('mobile',$request->mobile)->where('type',"Vendor")->where('id','!=',$request->row_id)->count();
					if($mobileExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Mobile no already exist')); die;
					}
					$emailExist = self::$UserModal->where('email',$request->email)->where('type',"Vendor")->where('id','!=',$request->row_id)->count();
					if($emailExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Email address already exist')); die;
					}
					
					self::$UserModal->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{

                    $loginIDExist = self::$UserModal->where('login_id',$request->login_id)->where('type',"Vendor")->count();
					if($loginIDExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Login User ID already exist')); die;
					}
					
					$mobileExist = self::$UserModal->where('mobile',$request->mobile)->where('type',"Vendor")->count();
					if($mobileExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Mobile no already exist')); die;
					}
					$emailExist = self::$UserModal->where('email',$request->email)->where('type',"Vendor")->count();
					if($emailExist > 0){
						return json_encode(array('heading' => 'Error', 'msg' => 'Email address already exist')); die;
					}
					
					$record = self::$UserModal->CreateRecord($setData);

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
			$record = self::$UserModal->where('id',$request->rowId)->first();
			echo json_encode(array('heading' => 'Success', 'record' => $record));die;
		}
	}

	 # update password
    public function changePassword(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/panel/');}
		$validator = Validator::make($request->all(), [
			'cp_row_id' => ['required'],
			'cp_password' => ['required','min:6'],
			'cp_confirm_password' => 'required|same:cp_password'
		],[
		'cp_row_id.required' => 'Invalid reguest',
		'cp_password.required' => 'Please enter your password.',
		'cp_confirm_password.required' => 'Please enter your confirm password.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('cp_row_id')){
				return json_encode(array('heading'=>'Error','msg'=>$errors->first('cp_row_id')));die;
			}else if($errors->first('cp_password')){
				return json_encode(array('heading'=>'Error','msg'=>$errors->first('cp_password')));die;
			}else if($errors->first('cp_confirm_password')){
				return json_encode(array('heading'=>'Error','msg'=>$errors->first('cp_confirm_password')));die;
			}
		}else{
            $User = self::$UserModal->where(array('id' => $request->post('cp_row_id')))->first();
			if($User){
				$Password = password_hash($request->post('password'),PASSWORD_BCRYPT);
				self::$UserModal->where(array('id' => $request->post('cp_row_id')))->update(array('password' => $Password));
				return json_encode(array('heading'=>'Success','msg'=>'Password updated successfully'));die;
            }else{
                echo json_encode(array('heading'=>'Error','msg'=>'invalid User.'));
            }
		}
    }
}