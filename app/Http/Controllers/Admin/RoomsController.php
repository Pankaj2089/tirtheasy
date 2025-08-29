<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Rooms;
use App\Models\Hotels;
use App\Models\AmenityCategories;
use App\Models\RoomImages;
use App\Models\RoomPrices;
use App\Models\Facilities;
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
class RoomsController extends Controller{
    private static $Rooms;
    private static $TokenHelper;
    private static $AmenityCategories;
    private static $Facilities;
    private static $RoomImages;
    private static $RoomPrices;
    private static $Hotels;
    public function __construct(){
        self::$Rooms = new Rooms();
        self::$AmenityCategories = new AmenityCategories();
		self::$TokenHelper = new TokenHelper();
		self::$RoomImages = new RoomImages();
		self::$RoomPrices = new RoomPrices();
		self::$Facilities = new Facilities();
		self::$Hotels = new Hotels();
    }
    #admin dashboard page
    public function getList(Request $request, $hotel_id){
        if(!$request->session()->has('admin_email') || empty($hotel_id)){
            return redirect('/panel/');
        }

		$hotelData = self::$Hotels->where(array('id' => $hotel_id))->first();
        return view('/panel/rooms/index', compact('hotel_id','hotelData'));
    }
    public function listPaginate(Request $request, $hotel_id){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        $query = self::$Rooms->where('hotel_id', $hotel_id)->where('status', '!=', 3);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }
        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/rooms/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request, $hotel_id){
        if(!$request->session()->has('admin_email') || empty($hotel_id)){return redirect('/panel/');}
        if($request->input()){
            $validator = Validator::make($request->all(), [
                'hotel_id' => 'required',
				'title' => 'required',
				'price' => 'required',
				'no_of_rooms' => 'required',
				'no_of_guest' => 'required',
			], [
                'hotel_id.required' => 'Invalid request.',
				'title.required' => 'Please enter title.',
				'price.required' => 'Please enter room price.',
				'no_of_rooms.required' => 'Please enter no of rooms.',
				'no_of_guest.required' => 'Please enter no of guest.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('hotel_id')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('hotel_id')));die;
                }
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;
                }
				if($errors->first('price')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('price')));die;
                }
				if($errors->first('no_of_rooms')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('no_of_rooms')));die;
                }
				if($errors->first('no_of_guest')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('no_of_guest')));die;
                }
            } else {
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
						$destination = base_path().'/public/img/rooms/';
						$request->banner->move($destination, $actual_image_name);
                        $setData['image'] = $actual_image_name;
					}
				}
                $setData['hotel_id'] = $request->input('hotel_id');
				$setData['title'] = $request->input('title');
                $setData['price'] = $request->input('price');
                $setData['no_of_rooms'] = $request->input('no_of_rooms');
                $setData['no_of_guest'] = $request->input('no_of_guest');
                $setData['no_of_child'] = $request->input('no_of_child');
				
                $setData['no_of_single_rooms'] = $request->input('no_of_single_rooms');
                $setData['no_of_single_beds'] = $request->input('no_of_single_beds');
                $setData['no_of_guest_in_room'] = $request->input('no_of_guest_in_room');
                $setData['no_of_child_in_room'] = $request->input('no_of_child_in_room');
                $setData['no_of_double_rooms'] = $request->input('no_of_double_rooms');
                $setData['no_of_double_beds'] = $request->input('no_of_double_beds');
                $setData['no_of_guest_in_double_room'] = $request->input('no_of_guest_in_double_room');
                $setData['no_of_child_in_double_room'] = $request->input('no_of_child_in_double_room');

				$setData['extra_mattress'] = $request->input('extra_mattress');
				$setData['extra_mattress_price'] = $request->input('extra_mattress_price');
				$setData['room_size'] = $request->input('room_size');
				$setData['cancellation_policy'] = $request->input('cancellation_policy');
				$setData['amenities'] = is_array($request->input('amenities')) && count($request->input('amenities')) > 0 ? json_encode($request->input('amenities')):'';
				$record = self::$Rooms->CreateRecord($setData);
				echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'recordID'=> $record->id));die;
            }
        }
		
		$amenities = self::$AmenityCategories->with('amenities')->where('status',1)->orderBy('title', 'ASC')->get();
        return view('/panel/rooms/add-page',compact(['amenities','hotel_id']));
    }
	#editPage
    public function editPage(Request $request,$row_id){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'title' => 'required',
				'price' => 'required',
				'no_of_rooms' => 'required',
				'no_of_guest' => 'required',
			], [
				'title.required' => 'Please enter title.',
				'price.required' => 'Please enter room price.',
				'no_of_rooms.required' => 'Please enter no of rooms.',
				'no_of_guest.required' => 'Please enter no of guest.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;
                }
				if($errors->first('price')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('price')));die;
                }
				if($errors->first('no_of_rooms')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('no_of_rooms')));die;
                }
				if($errors->first('no_of_guest')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('no_of_guest')));die;
                }
            } else {
				
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
						$destination = base_path().'/public/img/rooms/';
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
				$setData['title'] = $request->input('title');
                $setData['price'] = $request->input('price');
                $setData['no_of_rooms'] = $request->input('no_of_rooms');
                $setData['no_of_guest'] = $request->input('no_of_guest');
                $setData['no_of_child'] = $request->input('no_of_child');
				$setData['cancellation_policy'] = $request->input('cancellation_policy');
				
                $setData['no_of_single_rooms'] = $request->input('no_of_single_rooms');
                $setData['no_of_single_beds'] = $request->input('no_of_single_beds');
                $setData['no_of_guest_in_room'] = $request->input('no_of_guest_in_room');
                $setData['no_of_child_in_room'] = $request->input('no_of_child_in_room');
                $setData['no_of_double_rooms'] = $request->input('no_of_double_rooms');
                $setData['no_of_double_beds'] = $request->input('no_of_double_beds');
                $setData['no_of_guest_in_double_room'] = $request->input('no_of_guest_in_double_room');
                $setData['no_of_child_in_double_room'] = $request->input('no_of_child_in_double_room');
				
				$setData['extra_mattress'] = $request->input('extra_mattress');
				$setData['extra_mattress_price'] = $request->input('extra_mattress_price');
				$setData['room_size'] = $request->input('room_size');
				$setData['amenities'] = is_array($request->input('amenities')) && count($request->input('amenities')) > 0 ? json_encode($request->input('amenities')):'';
				$setData['image'] = $actual_image_name;
				$record = self::$Rooms->where('id',$row_id)->update($setData);
				echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
            }
        }
		
		if($row_id > 0){
			$record = self::$Rooms->where('id',$row_id)->first();
			$amenities = self::$AmenityCategories->with('amenities')->where('status',1)->orderBy('title', 'ASC')->get();
			$room_images = self::$RoomImages->where('status','!=',3)->where('room_id',$record->id)->latest()->get();
            $facilities = self::$Facilities->where('status',1)->orderBy('title', 'ASC')->get();
			return view('/panel/rooms/edit-page',compact(['record','amenities', 'room_images','facilities']));
		}else{
			return redirect('/panel/hotels');
		}
    }

	############ update produt title ###############
	public function updateProductTitle(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        if($request->input()){
			$setData['id'] = $request->input('id');
			$setData[$request->input('field')] = $request->input('value');			
			self::$RoomImages->UpdateRecord($setData);
			echo "Success";die;
		}	
	}

	public function updateProductFile(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
		$msg['msg'] = 'Error';
		if(isset($request->file) && $request->file->extension() != ""){
			$validator = Validator::make($request->all(), [
				'file' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'
			]);
			if($validator->fails()){
				$errors = $validator->errors();
				return json_encode(array('heading'=>'Error','msg'=>$errors->first('file')));die;
			}else{
				$actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001,999999)).uniqid(rand().true).$request->file('file')).'.'.$request->file->extension());
				$destination = base_path().'/public/img/rooms/';
				$request->file->move($destination, $actual_image_name);
				$setData['image'] = $actual_image_name;
				if($request->input('old_image') != ""){
					if(file_exists($destination.$request->input('old_image'))){
						unlink($destination.$request->input('old_image'));
					}
				}
				$setData['id'] = $request->input('id');
				self::$RoomImages->UpdateRecord($setData);
				$msg['msg'] = 'Success';
			}
		}
		echo json_encode(array('data'=>$msg));die;
	}

    /************** uploadProductImages ************/
	public function uploadProductImages(Request $request){
        //profile image
		if(!empty($_FILES)){
			$postData = $request->all();
			$msg = "Error";
			$fileName = $_FILES['file']['name']; //Get the image
			$file_temp_name = $_FILES['file']['tmp_name'];
			$pathInfo = pathinfo(basename($fileName));
			$ext = $request->file->extension();
			$checkImage = getimagesize($file_temp_name);
			$actual_image_name = sha1(str_shuffle(microtime(true).mt_rand(100001,999999)).uniqid(mt_rand().true).$request->file('file')).'.'.$request->file->extension();
			$destination2 = base_path().'/public/img/rooms/';
			if($checkImage !== false){
				if($request->file->move($destination2, $actual_image_name)){						
					$setData['room_id'] = $request->input('room_id');
					$setData['image'] = $actual_image_name;
					$record = self::$RoomImages->CreateRecord($setData);
					
					$msg = "Success";
				}
			}
		}
		echo json_encode(array('msg' => $msg));
	}


    #get Price Faqs
    public function getPrice(Request $request){
		$validator = Validator::make($request->all(), [
			'rowId' => 'required|numeric', 
		], [
			'rowId.required' => 'invalid Request.'
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('rowId')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('rowId')));
				die;
			}
		}else{
			$records = self::$RoomPrices->where('room_id',$request->rowId)->where('status','!=', 3)->get();
			return view('/panel/rooms/prices',compact(['records']));
		}
	}

    #add new faq
    public function addPrice(Request $request){
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'price' => 'required', 
			], [
				'price.required' => 'Please enter price.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('price')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('price')));
                    die;
                }
            } else {
				$setData['price'] = $request->input('price');
                $setData['no_of_guest'] = $request->input('no_of_guest');
                $setData['no_of_child'] = $request->input('no_of_child');
                $setData['no_of_rooms'] = $request->input('no_of_rooms');
				$setData['hotel_id'] = $request->input('hotel_id');
				$setData['room_id'] = $request->input('room_id');
				$setData['amenities'] = count($request->input('amenities')) > 0 ? json_encode($request->input('amenities')):'';
				if($request->input('row_id') > 0){
					self::$RoomPrices->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
					$record = self::$RoomPrices->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }
        }
    }
}