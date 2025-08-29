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

use App\Models\Settings;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
	private static $UserModel;
    private static $TokenHelper;
    private static $Settings;
	
	public function __construct(){
		self::$UserModel = new AdminUser();
        self::$TokenHelper = new TokenHelper();
        self::$Settings = new Settings();
	}
    # profile
    public function profile (Request $request){
		if(!$request->session()->has('admin_email')){ return redirect('/panel/'); }
		$userData = self::$UserModel->where(array('id' => $request->session()->get('admin_id')))->first();
        return view('/panel/profile/profile',compact('userData'));
    }
    # update profile page
    public function saveProfile(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/panel/');}
		$validator = Validator::make($request->all(), [
			'name' => 'required|regex:/^[\pL\s\-]+$/u',
			'address' => 'required',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
			'zipcode' => 'required|numeric'
			
		],[
		'name.required' => 'Please enter your name.',
		'name.alpha' => 'Please enter valid name.',
		'address.required' => 'Please enter address.',
		'city.required' => 'Please enter city.',
		'state.required' => 'Please enter state.',
		'country.required' => 'Please enter country.',
		'zipcode.required' => 'Please enter zipcode.'
		
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('name')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('name')));
				die;
			}
			if($errors->first('address')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('address')));
				die;
			}
			if($errors->first('city')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('city')));
				die;
			}
			if($errors->first('state')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('state')));
				die;
			}
			if($errors->first('zipcode')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('zipcode')));
				die;
			}
			if($errors->first('country')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('country')));
				die;
			}
		}else{
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
                    $destination = base_path().'/public/admin/images/profile/';
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
			self::$UserModel->where(array('id' => $request->session()->has('admin_id')))->update(array('photo' => $actual_image_name, 'name' => trim($request->input('name')), 'address' => trim($request->input('address')), 'city' => trim($request->input('city')), 'state' => trim($request->input('state')), 'country' => trim($request->input('country')), 'zipcode' => trim($request->input('zipcode'))));
            return json_encode(array('heading'=>'Success','msg'=>'Profile updated successfully'));die;
		}
    }

    # change password page
    public function changePassword(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        return view('/panel/profile/change_password');
    }

    # update password
    public function updatePassword(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/panel/');}
		$validator = Validator::make($request->all(), [
			'password' => ['required','min:6'],
			'confirm_password' => 'required|same:password'
		],[
		'password.required' => 'Please enter your password.',
		'confirm_password.required' => 'Please enter your confirm password.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('current_password')){
                return json_encode(array('heading'=>'Error','msg'=>$errors->first('current_password')));die;
			}else if($errors->first('password')){
				return json_encode(array('heading'=>'Error','msg'=>$errors->first('password')));die;
			}else if($errors->first('confirm_password')){
				return json_encode(array('heading'=>'Error','msg'=>$errors->first('confirm_password')));die;
			}
		}else{
            $User = self::$UserModel->where(array('id' => $request->session()->has('admin_id')))->first();
			if($User){
                $PasswordMatch = password_verify($request->current_password, $User->password);
                if(!$PasswordMatch){
                    echo json_encode(array('heading'=>'Error','msg'=>'Old password is incorrect'));
                }else{
                    $Password = password_hash($request->post('password'),PASSWORD_BCRYPT);
                    self::$UserModel->where(array('id' => $request->session()->has('admin_id')))->update(array('password' => $Password));
                    return json_encode(array('heading'=>'Success','msg'=>'Password updated successfully'));die;
                }
            }else{
                echo json_encode(array('heading'=>'Error','msg'=>'invalid User.'));
            }
		}
    }

	# change settings page
    public function settings(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/panel/');}
		$setting = self::$Settings->where(array('id' => 1))->first();
        return view('/panel/profile/settings',compact('setting'));
    }

	# update setting page
    public function saveSetting(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/panel/');}
		$validator = Validator::make($request->all(), [
			'admin_email' => 'required|email'
		],[
			'admin_email.required' => 'Please enter your email.',
			'admin_email.email' => 'Please enter valid email.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
            return json_encode(array('heading'=>'Error','msg'=>$errors->first('admin_email')));die;
		}else{
			# profile pic upload
			if(isset($request->logo) && $request->logo->extension() != ""){
                $validator = Validator::make($request->all(), [
                    'logo' => 'required|image|mimes:jpeg,png,jpg|max:20480'
                ]);
                if($validator->fails()){
                    $errors = $validator->errors();
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('logo')));die;
                }else{
                    $actual_image_name = time().mt_rand().'.'.$request->logo->extension();
                    $destination = base_path().'/public/admin/images/profile/';
                    $request->logo->move($destination, $actual_image_name);
                    if($request->input('old_banner') != ""){
                        if(file_exists($destination.$request->input('old_banner'))){
                            unlink($destination.$request->input('old_banner'));
                        }
                    }
                }
			}else{
				$actual_image_name = $request->input('old_banner');
			}
			
			$regContent['left_section_heading'] = trim($request->input('left_section_heading'));
			$regContent['left_section_content'] = trim($request->input('left_section_content'));
			$regContent['right_section_heading'] = trim($request->input('right_section_heading'));
			$regContent['right_section_content'] = trim($request->input('right_section_content'));

			$register_page_content = json_encode($regContent);

			self::$Settings->where(array('id' => 1))->update(
				array(
					'logo' => $actual_image_name, 
					'admin_email' => trim($request->input('admin_email')),
					'company_name' => trim($request->input('company_name')),
					'mobile' => trim($request->input('mobile')),
					'footer_content' => trim($request->input('footer_content')),
					'business_address' => trim($request->input('business_address')),
					'footer_info' => trim($request->input('footer_info')),
					'register_page_content' => $register_page_content
				)
			);
            return json_encode(array('heading'=>'Success','msg'=>'Settings information updated successfully'));die;
		}
    }
}
