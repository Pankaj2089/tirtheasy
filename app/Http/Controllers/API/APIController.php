<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Banners;
use App\Models\Promotions;
use App\Models\Newsletters;
use App\Models\PopularDestinations;
use App\Models\PremiumFacilities;
use App\Models\InnerPages;
use App\Models\Hotels;
use App\Models\HotelImages;
use App\Models\States;
use App\Models\Cities;
use App\Models\Facilities;
use App\Models\Amenities;
use App\Models\HotelFaqs;
use App\Models\HotelRules;
use App\Models\HotelDetails;
use App\Models\HotelLandmarks;
use App\Models\Rooms;
use App\Models\RoomImages;
use App\Models\RoomPrices;
use App\Models\AdminUser;
use App\Models\UserAccessCode;
use App\Models\Orders;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Languages;
use App\Models\CouponCodes;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

use Razorpay\Api\Api;

class APIController extends Controller{
    private static $Banners;
    private static $Promotions;
    private static $PopularDestinations;
    private static $PremiumFacilities;
    private static $Hotels;
    private static $TokenHelper;
    private static $rootURL;
    private static $Newsletters;
    private static $InnerPages;
    private static $HotelImages;
    private static $Facilities;
    private static $States;
    private static $Cities;
    private static $HotelFaqs;
    private static $HotelRules;
    private static $Amenities;
    private static $HotelDetails;
    private static $HotelLandmarks;
    private static $Rooms;
    private static $RoomImages;
    private static $RoomPrices;
    private static $UserModel;
    private static $UserAccessCode;
    private static $Orders;
    private static $CouponCodes;
    public function __construct(){
        self::$Banners = new Banners();
        self::$Promotions = new Promotions();
        self::$TokenHelper = new TokenHelper();
        self::$PopularDestinations = new PopularDestinations();
        self::$PremiumFacilities = new PremiumFacilities();
        self::$Hotels = new Hotels();
        self::$Newsletters = new Newsletters();
        self::$InnerPages = new InnerPages();
        self::$HotelImages = new HotelImages();
        self::$Facilities = new Facilities();
        self::$States = new States();
        self::$Cities = new Cities();
        self::$HotelFaqs = new HotelFaqs();
        self::$HotelRules = new HotelRules();
        self::$Amenities = new Amenities();
        self::$HotelDetails = new HotelDetails();
        self::$HotelLandmarks = new HotelLandmarks();
        self::$Rooms = new Rooms();
        self::$RoomImages = new RoomImages();
        self::$RoomPrices = new RoomPrices();
        self::$UserModel = new AdminUser();
        self::$UserAccessCode = new UserAccessCode();
        self::$Orders = new Orders();
        self::$CouponCodes = new CouponCodes();
        self::$rootURL = "http://localhost/tirtheasy/";
    }

     # admin dashboard page
    public function signIn(Request $request){

        $validator = Validator::make($request->all(), [
			'mobile' => 'required|min:10|numeric'
		],[
			'mobile.required' => 'Please enter your mobile number.',
			'mobile.min' => 'Please enter valid mobile number',
			'mobile.numeric' => 'Please enter valid mobile number'
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('mobile')){
				return response()->json(['success'=>false, 'message' => $errors->first('mobile')]);
			}
		}else{

			$otp = 123456;
			// $otp = rand(1001,9999);
			// $templateID = '650bd7bfd6fc0538e8048be2';
			// $messageData = ['mobiles' => '91'.$request->input('mobile'),'var1' => $otp,'var2' => '10 min'];
			// self::$SendSms->SendSMS($templateID,$messageData);
			
			$userData = self::$UserAccessCode->where('mobile',$request->input('mobile'))->first();
			if(isset($userData->id)){
				self::$UserAccessCode->where('id',$userData->id)->update(['otp' => $otp]);
			}else{
				$setData['otp'] = $otp;
				$setData['mobile'] = $request->input('mobile');
				self::$UserAccessCode->CreateRecord($setData);
			}
			return response()->json(['success'=>true],200);
			
		}

		// $validator = Validator::make($request->all(), [
		// 	'email' => 'required|email',
		// 	'password' => 'required',
		// ],[
		// 	'email.required' => 'Please enter your email address.',
		// 	'email.email' => 'Please enter valid email address.',
		// 	'password.required' => 'Please enter your password.'
		// ]);

		// if($validator->fails()){
		// 	 $errors = $validator->errors();
		// 	if($errors->first('email')){
        //         return json_encode(array('heading' => 'Error', 'msg' => $errors->first('email'))); die;
		// 	}else if($errors->first('password')){
		// 		return json_encode(array('heading' => 'Error', 'msg' => $errors->first('password'))); die;
		// 	}
		// }else{
		// 	$User = self::$UserModel->select(['id', 'name', 'email', 'mobile'])->where(array('email' => $request->email))->where('status',1)->first();
		// 	if($User){
		// 		$PasswordMatch = password_verify($request->input('password'), $User->password);
		// 		if(!$PasswordMatch){
        //              return json_encode(array('heading' => 'Error', 'msg' => 'Username and password incorrect')); die;
		// 		}else{
        //             $userId = $User->id;
		// 			$secret = env('JWT_KEY');
		// 			$expiration = time() + 2592000;
		// 			$issuer = 'aayushbharat.com';
		// 			$accessToken = Token::create($userId, $secret, $expiration, $issuer);
        //             return json_encode(array('heading' => 'Success', 'msg' => 'loggedin successfully.', 'accessToken' => $accessToken, 'userDetails' => $User)); die;
		// 		}
		// 	}else{
        //         return json_encode(array('heading' => 'Error', 'msg' => 'Username and password incorrect')); die;
		// 	}
		// }
    }

    public function verifyOTP(Request $request){
		$validator = Validator::make($request->all(), [
			'mobile' => 'required|min:10|numeric',
			'otp' => 'required|numeric'
		],[
			'mobile.required' => 'Please enter your mobile number.',
			'mobile.min' => 'Please enter valid mobile number',
			'mobile.numeric' => 'Please enter valid mobile number',
			'otp.required' => 'Please enter otp.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('mobile')){
				return response()->json(['success'=>false, 'msg' => $errors->first('mobile')]);
			}
			if($errors->first('otp')){
				return response()->json(['success'=>false, 'msg' => $errors->first('otp')]);
			}
		}else{
			$checkOtp = self::$UserAccessCode->where('mobile',$request->input('mobile'))->where('otp',$request->input('otp'))->count();
			if($checkOtp == 0){
				return response()->json(['success'=>false, 'msg' => 'Please enter valid OTP.']);
			}
			$userData = self::$UserModel->where('mobile',$request->input('mobile'))->first();

			if(isset($userData->id)){
                $userId = $userData->id;
                $secret = env('JWT_KEY');
                $expiration = time() + 2592000;
                $issuer = 'tirtheasy.com';
                $token = Token::create($userId, $secret, $expiration, $issuer);
                $User = self::$UserModel->select(['id', 'name', 'email', 'mobile'])->where('id',$userId)->first();

                self::$UserAccessCode->where('mobile',$request->input('mobile'))->where('otp',$request->input('otp'))->delete();

                return response()->json(['success'=>true,'accessToken'=>$token,'userDetails'=>$User],200);
			}else{
				return response()->json(['success'=>true, 'register' =>true],200);
			}

		}
	}

    # admin dashboard page
    public function userRegister(Request $request){

		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
			'name' => 'required',
		],[
			'email.required' => 'Please enter your email address.',
			'email.email' => 'Please enter valid email address.',
			'name.required' => 'Please enter your name.'
		]);

		if($validator->fails()){
			 $errors = $validator->errors();
			if($errors->first('name')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('password'))); die;
			}else if($errors->first('email')){
                return json_encode(array('heading' => 'Error', 'msg' => $errors->first('email'))); die;
			} 
		}else{
			$User = self::$UserModel->where('email', $request->input('email'))->where('status',1)->first();
			if(isset($User->id)){
                return response()->json(['success'=>false, 'msg' => 'Email Address already registered.']);
			}else{

                $password = password_hash(123456,PASSWORD_BCRYPT);
                $setData['type'] = 'User';
                $setData['name'] = $request->input('name');
                $setData['email'] = $request->input('email');
                $setData['password'] = $password;
                $setData['mobile'] = $request->input('mobile');
                $userData = self::$UserModel->CreateRecord($setData);

                $userId = $userData->id;
                $secret = env('JWT_KEY');
                $expiration = time() + 2592000;
                $issuer = 'tirtheasy.com';
                $token = Token::create($userId, $secret, $expiration, $issuer);
                $User = self::$UserModel->select(['id', 'name', 'email', 'mobile'])->where('id',$userId)->first();

                self::$UserAccessCode->where('mobile',$request->input('mobile'))->where('otp',$request->input('otp'))->delete();

                return response()->json(['success'=>true,'accessToken'=>$token,'userDetails'=>$User],200);
			}
		}
    }
	
    #get sliders api
    public function getBannersList(Request $request){
        $records = self::$Banners->where('status', 1)->whereNotNull('banner')->orderBy('id', 'DESC')->limit(10)->get();
        if(count($records) > 0){
            foreach($records as $record){
                if(isset($record->banner) && $record->banner != ""){
                    $record->banner =  self::$rootURL.'public/img/banners/'.$record->banner;
                }

            }
        }
        return response()->json(['success'=>true, 'records' => $records],200);
    }
    
    #get promotions api
    public function getPromotionsList(Request $request){
        $records = self::$Promotions->where('status', 1)
        ->where('promotion_type', $request->input('type'))
        ->where('promotion_for', $request->input('promotion_for'))
        ->whereNotNull('banner')
        ->orderBy('id', 'DESC')
        ->limit(10)->get();
        if(count($records) > 0){
            foreach($records as $record){
                if(isset($record->banner) && $record->banner != ""){
                    $record->banner = self::$rootURL.'public/img/promotions/'.$record->banner;
                }
            }
        }
        return response()->json(['success'=>true, 'records' => $records],200);
    }
    
    #get popular destinations list api
    public function getPopularDestinationsList(Request $request){
        $records = self::$PopularDestinations->where('status', 1)
        ->where('destination_type', $request->input('type'))
        ->where('property_type', $request->input('property_type'))
        ->whereNotNull('image')
        ->orderBy('id', 'ASC')
        ->limit(15)
        ->get();
        if(count($records) > 0){
            foreach($records as $record){
                if(isset($record->image) && $record->image != ""){
                    $record->image = self::$rootURL.'public/img/popular-destinations/'.$record->image;
                }
            }
        }
        return response()->json(['success'=>true, 'records' => $records],200);
    }
    
    #get premium facilities list api
    public function getPremiumFacilities(Request $request){
        $records = self::$PremiumFacilities->where('status', 1)
        ->where('facility_type', $request->input('facility_type'))
        ->orderBy('id', 'ASC')->limit(9)->get();
        return response()->json(['success'=>true, 'records' => $records],200);
    }
    
    #save news letters api
    public function saveNewsletter(Request $request){
        if($request->input()){

            $validator = Validator::make($request->all(), [
				'email' => 'required|email',  
			],[
				'email.required' => 'Please enter email.',
                'email.email' => 'Please enter valid email.',
			]);

            if($validator->fails()){
                $errors = $validator->errors();
				if($errors->first('email')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('email'))); die;}
            }else{
                    $Newsletter = self::$Newsletters->where(array('email' => $request->email))->where('status',1)->first();
                    if(!$Newsletter){
                        $setData['email'] = $request->input('email');
                        $setData['status'] =1;
                        $record = self::$Newsletters->CreateRecord($setData);
                    }
                    return response()->json(['success'=>true, 'msg' => "Your email address saved."],200);
                }
            }else{
                return json_encode(array('heading' => 'Error', 'msg' => "Please enter email.")); die;
            }

    }

    #get hotels/ dharamshala list  api
    public function getHotelsList(Request $request, $type){
        $records = self::$Hotels->where('status', 1)->where('browse_top', 1)->where('hotel_type', $type)->orderBy('id', 'ASC')->limit(20)->get();
        if(count($records) > 0){
            foreach($records as $record){
                if(isset($record->image) && $record->image != ""){
                    $record->image = self::$rootURL.'public/img/hotel/'.$record->image;
                }
            }
        }
        return response()->json(['success'=>true, 'records' => $records],200);
    }   
    
    #get get static page api
    public function getStaticPage(Request $request, $id){
        $record = self::$InnerPages->where('status', 1)->where('id', $id)->orderBy('id', 'ASC')->first();
        return response()->json(['success'=>true, 'record' => $record],200);
    } 

    #get property list api
    public function getPropertyList(Request $request){
         DB::statement("SET SQL_MODE=''");
        $recQry = self::$Hotels->join('rooms', 'hotel.id', '=', 'rooms.hotel_id')
        ->select('hotel.*', DB::raw('MIN(rooms.price) as lowest_price'));
        if($request->input('keywords')){
            $keywords = $request->input('keywords');
            $recQry->where(function($qr) use($keywords) {
				$qr->orWhere('hotel.title','like','%'.$keywords.'%');
				$qr->orWhere('hotel.city','like','%'.$keywords.'%');
				$qr->orWhere('hotel.state','like','%'.$keywords.'%');
			});
        }
        if($request->input('property_type')){
            $property_type = $request->input('property_type');
            $recQry->where('hotel.hotel_type',$property_type);
        }
        if($request->input('priceRange')){
            $priceRange = $request->input('priceRange');
            $recQry->whereBetween('rooms.price',$priceRange);
        }
        if($request->input('sortby') && $request->input('sortby') == 'lowest_price'){
            $recQry->orderBy('rooms.price', 'ASC');
        }else{
            $recQry->orderBy('hotel.title', 'ASC');
        }

        $recQry->where('hotel.status', 1)->groupBy('hotel.id');

       $records = $recQry->paginate(4);
        if(count($records) > 0){
            foreach($records as $record){
                $record->facilities_list = [];
                if(isset($record->image) && $record->image != ""){
                    $record->image = self::$rootURL.'public/img/hotel/'.$record->image;
                }
                # get additional images
                $additionalImages = self::$HotelImages->select(['image'])->where('status', 1)->where('hotel_id',$record->id)->get();
                if(count($additionalImages) > 0){
                    foreach($additionalImages as $additionalImage){
                        $additionalImage->image = self::$rootURL.'public/img/hotel/'.$additionalImage->image;
                    }
                }
                $record->additional_images = $additionalImages;

                # get facilities
                if(!empty($record->facilities)){
                    $facilitiesId = json_decode($record->facilities);
                    $record->facilities_list = self::$Facilities->select('title')->where('status', 1)->whereIn('id', $facilitiesId)->orderBy('title', 'ASC')->get();
                }
            }
        }
        DB::statement("SET SQL_MODE=only_full_group_by");
        return response()->json(['success'=>true, 'records' => $records],200);
    } 	
    
    #get cities
    public function getCities(Request $request){
        $recQry = self::$Cities->where('status', 1);
        if($request->input('keywords')){
            $keywords = $request->input('keywords');
            $recQry->where('title','like', '%'.$keywords.'%');
        }
        $records = $recQry->orderBy('title', 'ASC')
        ->limit(20)
        ->get();

        $recHotelQry = self::$Hotels->where('status', 1);
        if($request->input('keywords')){
            $keywords = $request->input('keywords');
            $recHotelQry->where('title','like', '%'.$keywords.'%');
        }
        if($request->input('property_type')){
            $property_type = $request->input('property_type');
            $recQry->where('hotel_type',$property_type);
        }
        $recHotels = $recHotelQry->orderBy('title', 'ASC')
        ->limit(20)
        ->get();

        return response()->json(['success'=>true, 'records' => $records, 'recHotels' => $recHotels],200);
    }
    
    #get property details api
    public function getPropertyData(Request $request, $slug){
        $record = self::$Hotels->where('status', 1)->where('slug', $slug)->orderBy('id', 'ASC')->first();
        if(isset($record->id)){
            $record->check_in_time = date('h:i A',strtotime($record->check_in_time));
            $record->check_out_time = date('h:i A',strtotime($record->check_out_time));
            $record->facilities_list = [];
            if(isset($record->image) && $record->image != ""){
                $record->image = self::$rootURL.'public/img/hotel/'.$record->image;
            }
            # get additional images
            $additionalImages = self::$HotelImages->select(['image'])->where('status', 1)->where('hotel_id',$record->id)->get();
            if(count($additionalImages) > 0){
                foreach($additionalImages as $additionalImage){
                    $additionalImage->image = self::$rootURL.'public/img/hotel/'.$additionalImage->image;
                }
            }
            $record->additional_images = $additionalImages;
           
        }
        return response()->json(['success'=>true, 'record' => $record],200);
    } 
    
    #get state cities
    public function getStateCities(Request $request){
        $records = self::$Cities->where('cities.status', 1)
        ->join('states', 'cities.state_id', '=', 'states.id')
        ->where('states.title', $request->input('state'))
        ->select('cities.*')
        ->orderBy('cities.title', 'ASC')
        ->get();
        return response()->json(['success'=>true, 'records' => $records],200);
    }
    
    #get hotel faqs
    public function getHotelFaqs(Request $request){
        $records= self::$HotelFaqs->where('status', 1)->where('hotel_id', $request->input('hotel_id'))->orderBy('id', 'ASC')->get();
        return response()->json(['success'=>true, 'records' => $records],200);
    }   
    
    #get recommended hotels
    public function getRecommendedHotels(Request $request){
        $records = self::$Hotels->where('status', 1)
        ->where('hotel_type', $request->input('type'))
        ->where('city', $request->input('hotel_city'))
        ->whereNot('id', $request->input('hotel_id'))
        ->limit(10)->get();
        if(count($records) > 0){
            foreach($records as $record){
                if(isset($record->image) && $record->image != ""){
                    $record->image = self::$rootURL.'public/img/hotel/'.$record->image;
                }
            }
        }
        return response()->json(['success'=>true, 'records' => $records],200);
    }  
    
    #get hotel rules
    public function getHotelRules(Request $request){
        $records= self::$HotelRules->where('status', 1)->where('hotel_id', $request->input('hotel_id'))->orderBy('id', 'ASC')->get();
        return response()->json(['success'=>true, 'records' => $records],200);
    }
    
    #get hotel facilities
    public function getHotelFacilities(Request $request){
        # get facilities
        $facilitiesId = json_decode($request->input('hotel_facilities'));
        $records = self::$Facilities->select(['title','icon'])
        ->where('status', 1)
        ->whereIn('id', $facilitiesId)
        ->orderBy('title', 'ASC')->get();

        if(count($records) > 0){
            foreach($records as $facility){
                if(!empty($facility->icon)){
                    $facility->icon = self::$rootURL.'public/img/facilities/'.$facility->icon;
                }
            }
        }
        return response()->json(['success'=>true, 'records' => $records],200);
    }
    
    #get hotel amenities
    public function getHotelAmenities(Request $request){
        # get amenities
        $facilitiesId = json_decode($request->input('hotel_amenities'));
        $records = self::$Amenities->select(['title','icon'])
        ->where('status', 1)
        ->whereIn('id', $facilitiesId)
        ->orderBy('title', 'ASC')->get();

        if(count($records) > 0){
            foreach($records as $facility){
                if(!empty($facility->icon)){
                    $facility->icon = self::$rootURL.'public/img/amenities/'.$facility->icon;
                }
            }
        }
        return response()->json(['success'=>true, 'records' => $records],200);
    }
    
    #get hotel details
    public function getHotelDetails(Request $request){
        $record= self::$HotelDetails->select([
            'special_note','extra_bed_policy', 
            'check_in_restrictions', 
            'food_arrangement','id_proof_related',
            'property_accessibility','pet_related','other_rules'
        ])
        ->where('hotel_id', $request->input('hotel_id'))
        ->orderBy('id', 'ASC')->first();
        $records = [];
        if ($record) {
           if(!empty($record->special_note)) $records[] = ['title' => 'special_note', 'heading' => 'Special Note', 'details' => $record->special_note];
           if(!empty($record->extra_bed_policy)) $records[] = ['title' => 'extra_bed_policy', 'heading' => 'Extra Bed Policy', 'details' => $record->extra_bed_policy];
           if(!empty($record->check_in_restrictions)) $records[] = ['title' => 'check_in_restrictions', 'heading' => 'Check In Restrictions', 'details' => $record->check_in_restrictions];
           if(!empty($record->food_arrangement)) $records[] = ['title' => 'food_arrangement', 'heading' => 'Food Arrangement', 'details' => $record->food_arrangement];
           if(!empty($record->id_proof_related)) $records[] = ['title' => 'id_proof_related', 'heading' => 'Id Proof Related', 'details' => $record->id_proof_related];
           if(!empty($record->property_accessibility)) $records[] = ['title' => 'property_accessibility', 'heading' => 'Property Accessibility', 'details' => $record->property_accessibility];
           if(!empty($record->pet_related)) $records[] = ['title' => 'pet_related', 'heading' => 'Pet(s) Related', 'details' => $record->pet_related];
           if(!empty($record->other_rules)) $records[] = ['title' => 'other_rules', 'heading' => 'Other Rules', 'details' => $record->other_rules];
        }
        return response()->json(['success'=>true, 'records' => $records],200);
    }
    
    #get hotel landmarks
    public function getHotelLandmarks(Request $request){
        # get landmarks
        DB::statement("SET SQL_MODE=''");
        $records = self::$HotelLandmarks->where('status', 1)
        ->where('hotel_id', $request->input('hotel_id'))
        ->orderBy('title', 'ASC')->get()->groupBy('category_id');
         DB::statement("SET SQL_MODE=only_full_group_by");

         if(count($records) > 0){
            foreach($records as $key => $record){                
                if(count($record) > 0){
                    foreach($record as $row){
                        if(isset($row->icon) && $row->icon != ""){
                            $row->icon = self::$rootURL.'public/img/landmarks/'.$row->icon;
                        }
                    }
                }
            }
        }

        return response()->json(['success'=>true, 'records' => (object)$records],200);
        
    }
    
    #get hotel rooms api
    public function getHotelRooms(Request $request){
        $records = self::$Rooms->where('status', 1)->where('hotel_id', $request->input('hotel_id'))->orderBy('price', 'ASC')->get();
        if(count($records) > 0){
             foreach($records as $record){
                $bedPreference =  'single';
                if($record->no_of_single_rooms <= 0 && $record->no_of_double_rooms > 0){
                    $bedPreference =  'double';
                }
                $record->bedPreference = $bedPreference;
                if(isset($record->image) && $record->image != ""){
                    $record->image = self::$rootURL.'public/img/rooms/'.$record->image;
                }

                # get additional images
                $additionalImages = self::$RoomImages->select(['image'])->where('status', 1)->where('room_id',$record->id)->get();
                if(count($additionalImages) > 0){
                    foreach($additionalImages as $additionalImage){
                        $additionalImage->image = self::$rootURL.'public/img/rooms/'.$additionalImage->image;
                    }
                }
                $record->additional_images = $additionalImages;

                $roomPriceIDs = [];
                # get prices
                if($request->input('start_date') && !empty($request->input('start_date'))){
                 // check room already booked
                    $checkInDate = date('Y-m-d',strtotime($request->input('start_date')));
                    $checkOutDate = date('Y-m-d',strtotime($request->input('end_date')));;
                    $orderRows =self::$Orders
                    ->where('room_id', $record->id)
                    ->where(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                        ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                        ->orWhere(function ($q2) use ($checkInDate, $checkOutDate) {
                            $q2->where('check_in_date', '<=', $checkInDate)
                                ->where('check_out_date', '>=', $checkOutDate);
                        });
                    })->get();
                    if(count($orderRows) > 0){
                        foreach($orderRows as $orderRow){
                            $roomPriceIDs[] = $orderRow->room_price_id;
                        }
                    }

                }
                    
                $roomPricesQuery = self::$RoomPrices->where('status', 1)
                ->where('room_id',$record->id);
                // if(count($roomIDs) > 0){
                //     $roomPricesQuery->whereNotIn('id',$roomIDs);
                // }

                $roomPrices =  $roomPricesQuery->orderBy('price', 'ASC')->get();

                $record->room_prices = $roomPrices;
                if(count($record->room_prices) > 0){
                    foreach($record->room_prices as $price){
                        if(isset($price->amenities) && !empty($price->amenities)){
                            $price->amenities = json_decode($price->amenities);
                        }

                        $price->room_available = !in_array($price->id, $roomPriceIDs);
                    }
                }

                # get amenities
                $record->amenities_list = [];
                if(!empty($record->amenities)){
                    $amenitiesId = json_decode($record->amenities);
                    $amenities_list = self::$Amenities->where('status', 1)->whereIn('id', $amenitiesId)->orderBy('title', 'ASC')->get();
                    if(count($amenities_list) > 0){
                        foreach($amenities_list as $amenity){
                            $amenity->icon = self::$rootURL.'public/img/amenities/'.$amenity->icon;
                        }
                    }
                    $record->amenities_list = $amenities_list;
                }
                
            }
        }
        return response()->json(['success'=>true, 'records' => $records],200);
    } 

    #get room price details
    public function getRoomPriceDetails(Request $request){
        $record = self::$Rooms->where('status', 1)->where('id', $request->input('room_id'))->first();
        $record->room_price = self::$RoomPrices->where('status', 1)->where('room_id',$record->id)->where('id',$request->input('room_price_id'))->orderBy('price', 'ASC')->first();
        return response()->json(['success'=>true, 'records' => $record],200);
    } 

    #get states
    public function getStates(Request $request){
        $states = DB::table('states')->select(['id', 'title'])->where('status',1)->orderBy('title')->get();
        return response()->json(['success'=>true, 'records' => $states],200);
	}

    #get state cities list
    public function getStateCitiesList(Request $request){
        $cities = DB::table('cities')
        ->select(['id', 'title'])
        ->where('status',1)
        ->where('state_id',$request->input('state_id'))
        ->orderBy('title')
        ->get();
        return response()->json(['success'=>true, 'records' => $cities],200);
	}

    #create order
    public function createOrder(Request $request){

        if($request->input()){
            $validator = Validator::make($request->all(), [
				'adults' => 'required', 
				'rooms' => 'required', 
				'end_date' => 'required', 
				'start_date' => 'required', 
				'end_date' => 'required', 
			], [
                'adults.required' => 'Please select no. of audlt(s).',
				'rooms.required' => 'Please select no. of room(s).',
                'start_date.required' => 'Please select check-in date.',
				'end_date.required' => 'Please select check-out date.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('adults')){
                    return response()->json(['success'=>false,'message' => $errors->first('adults')],200);
                }
                if($errors->first('rooms')){
                    return response()->json(['success'=>false,'message' => $errors->first('rooms')],200);
                }
                if($errors->first('start_date')){
                    return response()->json(['success'=>false,'message' => $errors->first('start_date')],200);
                }
                if($errors->first('end_date')){
                    return response()->json(['success'=>false,'message' => $errors->first('end_date')],200);
                }
                
            } else {
                // check room 
                $roomRow = self::$Rooms->where('status', 1)->where('id', $request->input('room_id'))->orderBy('price', 'ASC')->first();
                if(isset($roomRow->id)){
                    // check room price
                    $roomPriceRow = self::$RoomPrices->where('status', 1)->where('room_id',$roomRow->id)->where('id',$request->input('room_price_id'))->orderBy('price', 'ASC')->first();
                    if(isset($roomRow->id)){
                        // check room already booked
                        $checkInDate = date('Y-m-d',strtotime($request->input('start_date')));
                        $checkOutDate = date('Y-m-d',strtotime($request->input('end_date')));;
                        
                        $orderRow =self::$Orders
                        ->where('room_id', $roomRow->id)
                        ->where('room_price_id', $request->input('room_price_id'))
                        ->where(function ($q) use ($checkInDate, $checkOutDate) {
                            $q->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                            ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                            ->orWhere(function ($q2) use ($checkInDate, $checkOutDate) {
                                $q2->where('check_in_date', '<=', $checkInDate)
                                    ->where('check_out_date', '>=', $checkOutDate);
                            });
                        })
                        ->exists();

                        if(!$orderRow){

                            $userExist = self::$UserModel->where(array('mobile' => $request->user_mobile))->first();	
                            if(isset($userExist->id)){
                                $this->UpdateUser($request,$userExist->id);
                                $userID = $userExist->id;
                            }else{
                                return response()->json(['success'=>false,'message' => 'Please login with your account.'],200);
                            }

                            $lastOrder = self::$Orders->select('in_no','id')->orderBy('id','DESC')->first();
                            $inc_no = 1;
                            if(isset($lastOrder->id)){
                                $inc_no = $lastOrder->in_no+1;
                            }

                            // set order data
                            $inc_no = str_pad($inc_no, 3, "0", STR_PAD_LEFT);

                            // get hotel record
                            $hotelRow= self::$Hotels->where('status', 1)->where('id',$roomRow->hotel_id)->first();

                            $roomDetails['hotel_details'] = $hotelRow->toArray();
                            $roomDetails['room_details'] = $roomRow->toArray();

                            $otherDetals = json_encode(['userDetails' => $request->input('user_data'), 'roomDetails'=> $roomDetails]);
                            
                            $extraMattressPrice = 0;
                            $subTotal = 0;
                            $grandTotal = 0;
                            if($request->input('extra_mattress') && $request->input('extra_mattress') > 0){
                                 $extraMattressPrice = intval($request->input('extra_mattress')) * floatval($roomRow->extra_mattress_price);
                            }
                            if($roomPriceRow->price > 0){
                                 $subTotal = intval($request->input('rooms')) * floatval($roomPriceRow->price);
                            }
                            if($roomPriceRow->price > 0){
                                 $grandTotal = floatval($subTotal) + floatval($extraMattressPrice);
                            }    

                            #coupon applied
                            if(floatval($request->input('coupon_amtount')) > 0){
                                $grandTotal = floatval($grandTotal) - floatval($request->input('coupon_amtount'));
                            }

                            $setOrderData['invoice_id'] = date('Y').'/'.date('m').'/N'.$inc_no;
			                $setOrderData['in_no'] = $inc_no;
                            $setOrderData['user_id'] = $userID;
                            $setOrderData['hotel_id'] = $roomRow->hotel_id;
                            $setOrderData['room_id'] = $request->input('room_id');
                            $setOrderData['room_price_id'] = $request->input('room_price_id');
                            $setOrderData['adults'] = $request->input('adults');
                            $setOrderData['childs'] = $request->input('childerns');
                            $setOrderData['rooms'] = $request->input('rooms');
                            $setOrderData['extra_mattress'] = $request->input('extra_mattress');
                            $setOrderData['extra_mattress_price'] = $extraMattressPrice;
                            $setOrderData['sub_total'] = $subTotal;
                            $setOrderData['grand_total'] = $grandTotal;
                            $setOrderData['billing_name'] = $request->input('user_name');
                            $setOrderData['billing_email'] = $request->input('user_email');
                            $setOrderData['billing_phone'] = $request->input('user_mobile');
                            $setOrderData['billing_city'] = $request->input('user_data')['city'];
                            $setOrderData['billing_state'] = $request->input('user_data')['state'];
                            $setOrderData['payment_status'] ="PENDING";
                            $setOrderData['check_in_date'] = $checkInDate;
                            $setOrderData['check_out_date'] = $checkOutDate;
                            $setOrderData['order_platform'] = "WEB APP";
                            $setOrderData['payment_method_type'] = "PREPAID";
                            $setOrderData['other_details'] = $otherDetals;
                            $setOrderData['coupon_code'] = $request->input('coupon_code');
                            $setOrderData['discount'] = $request->input('coupon_amtount');
                            $orderData = self::$Orders->CreateRecord($setOrderData);
			                $order_id = $orderData->id;

                            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                            $order = $api->order->create([
                                'receipt' => 'rcptid_' . time(),
                                'amount' => $grandTotal * 100, // amount in paise
                                'currency' => 'INR',
                            ]);

                            $data = [
                                'order_id' => $order['id'],
                                'amount' => $order['amount'],
                                'currency' => $order['currency'],
                                'key' => env('RAZORPAY_KEY'),
                                'o_id' => $order_id
                            ];

                            return response()->json(['success'=>true, 'data' => $data ],200);
                        }else{
                            return response()->json(['success'=>false,'message' => 'Room is already booked for selected dates.'],200);
                        }
                    }else{
                        return response()->json(['success'=>false,'message' => 'invalid request'],200);
                    }
                }else{
                    return response()->json(['success'=>false,'message' => 'invalid request'],200);
                }
            }
        }
        
    }

    #verify payment
    public function verifyPayment(Request $request)
    {
        $signatureStatus = false;

        $generatedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . "|" . $request->razorpay_payment_id,
            env('RAZORPAY_SECRET')
        );

        if ($generatedSignature === $request->razorpay_signature) {
            $signatureStatus = true;
            $setOrderData['payment_status'] ="SUCCESS";
            $setOrderData['txn_id'] = $request->razorpay_payment_id;
            $setOrderData['id'] = $request->o_id;
            $orderData = self::$Orders->UpdateRecord($setOrderData);
        }

        // Get the payment ID from the front-end (POST request)
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
			$payment_id = $request->razorpay_payment_id;
			$payment = $api->payment->fetch($payment_id);
			
			if($payment->status == 'authorized') {
				$payment->capture(['amount' => $payment->amount]); // amount in paise
			}

        return response()->json(['success' => $signatureStatus]);
    }

    #update user
    public function UpdateUser($request,$UserID){
		$setUserData['name'] = $request->user_name;
		$setUserData['state'] = $request->user_state;
		$setUserData['city'] = $request->user_city;
		self::$UserModel->where('id', $UserID)->update($setUserData);
		return $UserID;
	}

    #get coupons
    public function getCouponCodes(Request $request){
        $today = date('Y-m-d');
        $couponCodes = self::$CouponCodes->where('status', 1)
        ->whereDate('start_date', '<=', $today)
        ->whereDate('end_date', '>=', $today)
        ->orderBy('title', 'ASC')->get();

        return response()->json(['success'=>true, 'records' => $couponCodes],200);
    }
     #get coupons
    public function deleteTempOrder(Request $request){
        self::$Orders->where('id',$request->input('o_id'))->delete();
        return response()->json(['success'=>true],200);
    }

    
}