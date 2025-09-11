<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Contacts;
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

class ContactUsController extends Controller{

    private static $Contacts;
    private static $TokenHelper;

    public function __construct(){
        self::$Contacts = new Contacts();
		self::$TokenHelper = new TokenHelper();
    }

    #admin dashboard page
    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        return view('/panel/contacts/index');

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        $query = self::$Contacts->where('status', '!=', 3)->where('type','ContactUs');
        
        $SearchKeyword = $request->input('search_title');
        if(!empty($SearchKeyword)) {
                $query->where('name', 'like', '%'.$SearchKeyword.'%')->orWhere('email', 'like', '%'.$SearchKeyword.'%')->orWhere('contact', $SearchKeyword);
            }
		// if($request->input('search_type') && $request->input('search_type') != ""){
        //     $query->where('type', 'like', '%' . $request->input('search_type') . '%');
        // }
        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/contacts/paginate', compact('records'));

    }


     #admin dashboard page
    public function getBookingList(Request $request){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        return view('/panel/contacts/booking_index');
    }

    public function listBookingPaginate(Request $request){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        $query = self::$Contacts->where('status', '!=', 3)->where('type','BookingEnquiry');
        
        $SearchKeyword = $request->input('search_title');
        if(!empty($SearchKeyword)) {
            $query->where('name', 'like', '%'.$SearchKeyword.'%')->orWhere('email', 'like', '%'.$SearchKeyword.'%')->orWhere('contact', $SearchKeyword);
        }
        $records = $query->orderBy('id', 'DESC')->paginate(20);
      
        return view('/panel/contacts/booking_paginate', compact('records'));
    }

    #editPage
    public function viewBookingEnquiryPage(Request $request,$row_id){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
		if($row_id > 0){
			$record = self::$Contacts->where('id',$row_id)->first();
			return view('/panel/contacts/view-booking-enquiry-page',compact(['record']));
		}else{
			return redirect('/panel/booking-enquiries');
		}
    }
}