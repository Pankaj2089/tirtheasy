<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\RoomNumbers;
use App\Models\Rooms;
use App\Models\AmenityCategories;
use App\Models\Amenities;
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
class RoomNumbersController extends Controller{
    private static $RoomNumbers;
    private static $TokenHelper;
    private static $AmenityCategories;
    private static $Facilities;
    private static $RoomImages;
    private static $RoomPrices;
    private static $Rooms;
    private static $Amenities;
    public function __construct(){
        self::$RoomNumbers = new RoomNumbers();
        self::$AmenityCategories = new AmenityCategories();
        self::$Amenities = new Amenities();
		self::$TokenHelper = new TokenHelper();
		self::$RoomImages = new RoomImages();
		self::$RoomPrices = new RoomPrices();
		self::$Facilities = new Facilities();
		self::$Rooms = new Rooms();
    }
    #admin dashboard page
    public function getList(Request $request, $room_id){
        if(!$request->session()->has('admin_email') || empty($room_id)){
            return redirect('/panel/');
        }

		$roomData = self::$Rooms->where(array('id' => $room_id))->first();
        return view('/panel/room_numbers/index', compact('room_id','roomData'));
    }
    public function listPaginate(Request $request, $room_id){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        $query = self::$RoomNumbers->where('room_id', $room_id)->where('status', '!=', 3);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }
        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/room_numbers/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request, $room_id){
        if(!$request->session()->has('admin_email') || empty($room_id)){return redirect('/panel/');}
        if($request->input()){
            $validator = Validator::make($request->all(), [
                'room_id' => 'required',
			], [
                'room_id.required' => 'Invalid request.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('room_id')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('room_id')));die;
                }
            } else {
				
                if($request->input('type') == "bulk_add"){
                    if(is_numeric($request->input('from')) && $request->input('from') > 0){
                        $from = $request->input('from');
                        $to = $request->input('to');
                        $room_id = $request->input('room_id');
                        $hotel_id = $request->input('hotel_id');
                        for ($i = $from; $i <= $to; $i++) {
                            $fullTitle = !empty($request->input('prefix'))?$request->input('prefix').'-'.$i : $i;
                            $roomDataExist = self::$RoomNumbers->where('room_id', $room_id)->where('hotel_id', $hotel_id)->where('full_title', $fullTitle)->first();
                            if(!isset($roomDataExist->id)){
                                $setData['room_id'] = $room_id;
                                $setData['hotel_id'] = $hotel_id;
                                $setData['title'] = $i;
                                $setData['prefix'] = $request->input('prefix');
                                $setData['full_title'] = $fullTitle;
                                $record = self::$RoomNumbers->CreateRecord($setData);
                            }
                            if(isset($roomDataExist->status) && $roomDataExist->status == 3){
                                 $setData['status'] = 1;
                                 self::$RoomNumbers->where('id',$roomDataExist->id)->update($setData);
                            }
                        }
                    }
                }
                
                
                if($request->input('type') == "manually_add"){
                    if(count($request->input('prefix_bulk')) > 0){
                        $prefix = $request->input('prefix_bulk');
                        $room_numbers = $request->input('room_number');
                        $room_id = $request->input('room_id');
                        $hotel_id = $request->input('hotel_id');
                        foreach ($prefix as $key => $prefixName) {
                            $room_number = $room_numbers[$key];
                            $fullTitle = !empty($prefixName)?$prefixName.'-'.$room_number : $room_number;
                            $roomDataExist = self::$RoomNumbers->where('room_id', $room_id)->where('hotel_id', $hotel_id)->where('full_title', $fullTitle)->first();
                            if(!isset($roomDataExist->id)){
                                $setData['room_id'] = $room_id;
                                $setData['hotel_id'] = $hotel_id;
                                $setData['title'] = $room_number;
                                $setData['prefix'] = $prefixName;
                                $setData['full_title'] = $fullTitle;
                                $record = self::$RoomNumbers->CreateRecord($setData);
                            }
                            if(isset($roomDataExist->status) && $roomDataExist->status == 3){
                                 $setData['status'] = 1;
                                 self::$RoomNumbers->where('id',$roomDataExist->id)->update($setData);
                            }
                        }
                    }
                }

				echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
            }
        }

        $roomData = self::$Rooms->where(array('id' => $room_id))->first();
        $hotel_id = 0;
        if(isset($roomData->id)){
            $hotel_id = $roomData->hotel_id;
        }


        return view('/panel/room_numbers/add-page',compact(['room_id','hotel_id']));
    }
	#editPage
    public function editPage(Request $request,$row_id){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        if($request->input()){
            if($request->input('title') && !empty($request->input('title'))){
                 $room_id = $request->input('room_id');
                 $hotel_id = $request->input('hotel_id');
                $fullTitle = !empty($request->input('prefix'))?$request->input('prefix').'-'.$request->input('title') : $request->input('title');
                $roomDataExist = self::$RoomNumbers->where('room_id', $room_id)->where('hotel_id', $hotel_id)->where('full_title', $fullTitle)->where('id', '!=', $row_id)->first();
                if(!isset($roomDataExist->id)){
                    $setData['title'] = $request->input('title');
                    $setData['prefix'] = $request->input('prefix');
                    $setData['full_title'] = $fullTitle;
                    self::$RoomNumbers->where('id',$row_id)->update($setData);
                }else{
                    echo json_encode(array('heading' => 'Error', 'msg' => 'Record already exists'));die;
                }
            }

            echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;

        }

		if($row_id > 0){
			$record = self::$RoomNumbers->where('id',$row_id)->first();
			return view('/panel/room_numbers/edit-page',compact(['record']));
		}else{
			return redirect('/panel/hotels');
		}
    }

}