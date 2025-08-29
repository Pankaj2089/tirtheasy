<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;

use App\Mail\WelcomeEmail;
use Illuminate\Validation\Rule;

class UsersController extends Controller{
	
	private static $UserModel;
    private static $TokenHelper;
	
	public function __construct(){
		self::$UserModel = new AdminUser();
        self::$TokenHelper = new TokenHelper();
	}

	# userRegister
    public function userRegister(Request $request){
		if($request->input()){
			$validator = Validator::make($request->all(), [
                'name' => 'required',
				'email' => 'required|email',
				'phone' => 'required|min:10',
				'password' => [
							'required',
							// 'confirmed',
							Password::min(8)
								->mixedCase()
								->letters()
								->numbers()
								->symbols()
								->uncompromised(),
						],
            ],[
               	'name.required' => 'Please enter your full name.',
				'email.required' => 'Please enter your email.',
				'phone.required' => 'Please enter your phone number.',
				'password.required' => 'Please enter password.',
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('name')){
                    return json_encode(array('success'=>false,'msg'=>$errors->first('name')));die;
				}
				if($errors->first('email')){
                    return json_encode(array('success'=>false,'msg'=>$errors->first('email')));die;
				}
				if($errors->first('phone')){
                    return json_encode(array('success'=>false,'msg'=>$errors->first('phone')));die;
				}
				if($errors->first('password')){
                    return json_encode(array('success'=>false,'msg'=>$errors->first('password')));die;
				}
			}else{
				$emailCount = self::$UserModel->where('status','!=',3)->where('email',$request->input('email'))->count();
				if($emailCount > 0){
					return json_encode(array('success'=>false,'msg'=> 'Email alreay exists.'));die;
				}
				$countmobile = self::$UserModel->where('status','!=',3)->where('mobile',$request->input('phone'))->count();
				if($countmobile > 0){
					return json_encode(array('success'=>false,'msg'=> 'Mobile number alreay exists.'));die;
				}
				
				$setData['type'] = 'User';
				$setData['name'] = $request->input('name');
				$setData['email'] = $request->input('email');
				$setData['mobile'] = $request->input('phone');
				$setData['password'] = password_hash($request->input('password'),PASSWORD_BCRYPT);
				
				$insertData = self::$UserModel->CreateRecord($setData);

				#send Email
				$record = self::$UserModel->where('id',$insertData->id)->first();
				if(isset($record->id)){
					Mail::to($record->email)->send(new WelcomeEmail($record));
				}

				session(['username' => $setData['name']]);
				return json_encode(array('success'=>true,'msg'=> 'Welcome back! You’ve signed in successfully.'));die;
			}
		}
        die;
    }
}