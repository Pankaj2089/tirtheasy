<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminUser;
use App\Mail\WelcomeEmail;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;

class GoogleController extends Controller
{   
    private static $UserModel;
	
	public function __construct(){
		self::$UserModel = new AdminUser();
	}

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName();
        $emailCount = self::$UserModel->where('status','!=',3)->where('email',$email)->count();
        $password = $this->generateStrongPassword();
         if($emailCount == 0){
            $setData['type'] = 'User';
            $setData['name'] = $name;
            $setData['email'] = $email;
            $setData['mobile'] = "";
            $setData['password'] = password_hash($password,PASSWORD_BCRYPT);
            
            $insertData = self::$UserModel->CreateRecord($setData);
            #send Email
            $record = self::$UserModel->where('id',$insertData->id)->first();
            if(isset($record->id)){
                Mail::to($record->email)->send(new WelcomeEmail($record));
            }
         }
         session(['username' => $name]);
        return redirect()->intended('/')->with('success', 'Welcome back! You’ve signed in successfully.');
    }

    function generateStrongPassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+<>?';
        $charLength = strlen($chars);
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $charLength - 1)];
        }
        return $password;
    }
}