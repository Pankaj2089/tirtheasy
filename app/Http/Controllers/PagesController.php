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
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

class PagesController extends Controller{
	
	private static $UserModel;
    private static $TokenHelper;
	
	public function __construct(){
		self::$UserModel = new AdminUser();
        self::$TokenHelper = new TokenHelper();
	}
	
    # index
    public function index(Request $request){
        return view('/pages/index');
    }
    
    # sign up
    public function signUp(Request $request){
        if($request->session()->has('username')){return redirect('/');}
        return view('/pages/signup');
    }
    # about us
    public function aboutUs(Request $request){
        return view('/pages/aboutus');
    }
	 # contactUs
    public function contactUs(Request $request){

        return view('/pages/contact_us');
    }


}