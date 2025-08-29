<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Hotels;
use App\Models\States;
use App\Models\Cities;
use App\Models\AmenityCategories;
use App\Models\Facilities;
use App\Models\HotelImages;
use App\Models\HotelFaqs;
use App\Models\HotelRules;
use App\Models\HotelDetails;
use App\Models\HotelLandmarks;
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
class HotelsController extends Controller{
    private static $Hotels;
    private static $TokenHelper;
    private static $States;
    private static $Cities;
    private static $AmenityCategories;
    private static $Facilities;
    private static $HotelImages;
    private static $HotelFaqs;
    private static $HotelRules;
    private static $HotelDetails;
    private static $HotelLandmarks;
    public function __construct(){
        self::$Hotels = new Hotels();
        self::$States = new States();
        self::$Cities = new Cities();
        self::$AmenityCategories = new AmenityCategories();
        self::$Facilities = new Facilities();
		self::$TokenHelper = new TokenHelper();
		self::$HotelImages = new HotelImages();
		self::$HotelFaqs = new HotelFaqs();
		self::$HotelRules = new HotelRules();
		self::$HotelDetails = new HotelDetails();
		self::$HotelLandmarks = new HotelLandmarks();
    }
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        return view('/panel/hotels/index');
    }
    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        $query = self::$Hotels->where('status', '!=', 3);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }
		if($request->session()->get('admin_type') != 'Admin'){
			$query->where('added_by', $request->session()->get('admin_id'));
		}
        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/hotels/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'title' => 'required',
				'state' => 'required',
				'city' => 'required',
				'address' => 'required',
			], [
				'title.required' => 'Please enter title.',
				'state.required' => 'Please select state.',
				'city.required' => 'Please select city.',
				'address.required' => 'Please enter address.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;
                }
				if($errors->first('state')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('state')));die;
                }
				if($errors->first('city')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('city')));die;
                }
				if($errors->first('address')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('address')));die;
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
						$destination = base_path().'/public/img/hotel/';
						$request->banner->move($destination, $actual_image_name);
						$setData['image'] = $actual_image_name;
					}
				}
				
				$setData['added_by'] = $request->session()->get('admin_id');
				$setData['title'] = $request->input('title');
				$setData['slug'] = Str::slug($request->input('title'));
				$setData['hotel_type'] = $request->input('hotel_type');
				$setData['state'] = $request->input('state');
				$setData['city'] = $request->input('city');
				$setData['address'] = $request->input('address');
				$setData['amenities'] = $request->input('amenities') && count($request->input('amenities')) > 0 ? json_encode($request->input('amenities')):'';
				$setData['facilities'] = $request->input('facilities') && count($request->input('facilities')) > 0 ? json_encode($request->input('facilities')):'';
				$setData['check_in_time'] = $request->input('check_in_time');
				$setData['check_out_time'] = $request->input('check_out_time');
				$setData['policy'] = $request->input('policy');
				$setData['latitude'] = $request->input('latitude');
				$setData['longitude'] = $request->input('longitude');
				$setData['description'] = $request->input('description');
				$setData['seo_title'] = $request->input('seo_title');
				$setData['seo_description'] = $request->input('seo_description');
				$setData['seo_keyword'] = $request->input('seo_keyword');
				$setData['robot_tags'] = 'index,follow';
				$setData['price'] = $request->input('price');
				$setData['cancellation_policy'] = $request->input('cancellation_policy');
				$setData['is_only_jain'] = $request->input('is_only_jain');
				$record = self::$Hotels->CreateRecord($setData);

				$setHData['hotel_id'] = $record->id;
				$setHData['special_note'] = $request->input('special_note');
				$setHData['extra_bed_policy'] = $request->input('extra_bed_policy');
				$setHData['check_in_restrictions'] = $request->input('check_in_restrictions');
				$setHData['food_arrangement'] = $request->input('food_arrangement');
				$setHData['id_proof_related'] = $request->input('id_proof_related');
				$setHData['property_accessibility'] = $request->input('property_accessibility');
				$setHData['pet_related'] = $request->input('pet_related');
				$setHData['other_rules'] = $request->input('other_rules');
				self::$HotelDetails->CreateRecord($setHData);

				echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
            }
        }
		$states = self::$States->where('status',1)->orderBy('title', 'ASC')->get();
		$amenities = self::$AmenityCategories->with('amenities')->where('status',1)->orderBy('title', 'ASC')->get();
		$facilities = self::$Facilities->where('status',1)->orderBy('title', 'ASC')->get();
        return view('/panel/hotels/add-page',compact(['states','amenities','facilities']));
    }
	#editPage
    public function updateHotelStatus(Request $request){
		$setData['is_approve'] = $request->status;
		self::$Hotels->where('id',$request->row_id)->update($setData);
		echo 'Success'; die;
	}
	#editPage
    public function editPage(Request $request,$row_id){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        if($request->input()){
           $validator = Validator::make($request->all(), [
				'title' => 'required',
				'state' => 'required',
				'city' => 'required',
				'address' => 'required',
			], [
				'title.required' => 'Please enter title.',
				'state.required' => 'Please select state.',
				'city.required' => 'Please select city.',
				'address.required' => 'Please enter address.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;
                }
				if($errors->first('state')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('state')));die;
                }
				if($errors->first('city')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('city')));die;
                }
				if($errors->first('address')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('address')));die;
                }
            } else {
				
				# profile pic upload
				// if(isset($request->banner) && $request->banner->extension() != ""){
				// 	$validator = Validator::make($request->all(), [
				// 		'banner' => 'required|image|mimes:jpeg,png,jpg|max:2048'
				// 	]);
				// 	if($validator->fails()){
				// 		$errors = $validator->errors();
				// 		return json_encode(array('heading'=>'Error','msg'=>$errors->first('banner')));die;
				// 	}else{
				// 		$actual_image_name = time().'.'.$request->banner->extension();
				// 		$destination = base_path().'/public/img/hotel/';
				// 		$request->banner->move($destination, $actual_image_name);
				// 	}
				// }
				
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
						$destination = base_path().'/public/img/hotel/';
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
				$setData['slug'] = Str::slug($request->input('title'));
				$setData['title'] = $request->input('title');
				$setData['hotel_type'] = $request->input('hotel_type');
				$setData['state'] = $request->input('state');
				$setData['city'] = $request->input('city');
				$setData['address'] = $request->input('address');
				$setData['amenities'] = $request->input('amenities') && count($request->input('amenities')) > 0 ? json_encode($request->input('amenities')):'';
				$setData['facilities'] = $request->input('facilities') && count($request->input('facilities')) > 0 ? json_encode($request->input('facilities')):'';
				$setData['check_in_time'] = $request->input('check_in_time');
				$setData['check_out_time'] = $request->input('check_out_time');
				$setData['policy'] = $request->input('policy');
				$setData['latitude'] = $request->input('latitude');
				$setData['longitude'] = $request->input('longitude');
				$setData['description'] = $request->input('description');
				$setData['seo_title'] = $request->input('seo_title');
				$setData['seo_description'] = $request->input('seo_description');
				$setData['seo_keyword'] = $request->input('seo_keyword');
				$setData['robot_tags'] = 'index,follow';
				$setData['image'] = $actual_image_name;
				$setData['price'] = $request->input('price');
				$setData['cancellation_policy'] = $request->input('cancellation_policy');
				$setData['is_only_jain'] = $request->input('is_only_jain');
				$record = self::$Hotels->where('id',$row_id)->update($setData);

				#save other details
				$setHData['hotel_id'] = $row_id;
				$setHData['special_note'] = $request->input('special_note');
				$setHData['extra_bed_policy'] = $request->input('extra_bed_policy');
				$setHData['check_in_restrictions'] = $request->input('check_in_restrictions');
				$setHData['food_arrangement'] = $request->input('food_arrangement');
				$setHData['id_proof_related'] = $request->input('id_proof_related');
				$setHData['property_accessibility'] = $request->input('property_accessibility');
				$setHData['pet_related'] = $request->input('pet_related');
				$setHData['other_rules'] = $request->input('other_rules');

				$detailsRow = self::$HotelDetails->where('hotel_id',$row_id)->first();
				if(isset($detailsRow->id)){
					$setHData['id'] = $detailsRow->id;
					self::$HotelDetails->UpdateRecord($setHData);
				}else{
					self::$HotelDetails->CreateRecord($setHData);
				}

				echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
            }
        }
		
		if($row_id > 0){
			$record = self::$Hotels->where('id',$row_id)->first();
			
			$record->special_note = '';
			$record->extra_bed_policy = '';
			$record->check_in_restrictions = '';
			$record->food_arrangement = '';
			$record->id_proof_related = '';
			$record->property_accessibility = '';
			$record->pet_related = '';
			$record->other_rules = '';

			$recordDetails = self::$HotelDetails->where('hotel_id',$row_id)->first();
			if(isset($recordDetails->id)){
				$record->special_note = $recordDetails->special_note;
				$record->extra_bed_policy = $recordDetails->extra_bed_policy;
				$record->check_in_restrictions = $recordDetails->check_in_restrictions;
				$record->food_arrangement = $recordDetails->food_arrangement;
				$record->id_proof_related = $recordDetails->id_proof_related;
				$record->property_accessibility = $recordDetails->property_accessibility;
				$record->pet_related = $recordDetails->pet_related;
				$record->other_rules = $recordDetails->other_rules;
			}
			
			$states = self::$States->where('status',1)->orderBy('title', 'ASC')->get();
			$cities = self::$Cities->join('states','states.id','=','cities.state_id')->select('cities.*')->where('cities.status',1)->where('states.title',$record->state)->orderBy('cities.title', 'ASC')->get();
			$amenities = self::$AmenityCategories->with('amenities')->where('status',1)->orderBy('title', 'ASC')->get();
			$facilities = self::$Facilities->where('status',1)->orderBy('title', 'ASC')->get();
			$hotel_images = self::$HotelImages->where('status','!=',3)->where('hotel_id',$record->id)->latest()->get();
			return view('/panel/hotels/edit-page',compact(['record','states','amenities', 'facilities', 'cities','hotel_images']));
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
			self::$HotelImages->UpdateRecord($setData);
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
				$destination = base_path().'/public/img/hotel/';
				$request->file->move($destination, $actual_image_name);
				$setData['image'] = $actual_image_name;
				if($request->input('old_image') != ""){
					if(file_exists($destination.$request->input('old_image'))){
						unlink($destination.$request->input('old_image'));
					}
				}
				$setData['id'] = $request->input('id');
				self::$HotelImages->UpdateRecord($setData);
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
			$destination2 = base_path().'/public/img/hotel/';
			if($checkImage !== false){
				if($request->file->move($destination2, $actual_image_name)){						
					$setData['hotel_id'] = $request->input('hotel_id');
					$setData['image'] = $actual_image_name;
					$record = self::$HotelImages->CreateRecord($setData);
					
					$msg = "Success";
				}
			}
		}
		echo json_encode(array('msg' => $msg));
	}

	#getHotelFaqs
    public function getHotelFaqs(Request $request){
		$validator = Validator::make($request->all(), [
			'rowId' => 'required|numeric', 
		], [
			'rowId.required' => 'Please enter rowId.'
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('title')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('rowId')));
				die;
			}
		}else{
			$records = self::$HotelFaqs->where('hotel_id',$request->rowId)->where('status','!=', 3)->get();
			return view('/panel/hotels/faqs',compact(['records']));
		}
	}

	#add new faq
    public function addHotelFaqs(Request $request){
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'question' => 'required', 
				'answer' => 'required', 
			], [
				'question.required' => 'Please enter question.',
				'answer.required' => 'Please enter answer.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('question')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('question')));
                    die;
                }
				if($errors->first('answer')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('answer')));
                    die;
                }
            } else {
				$setData['question'] = $request->input('question');
                $setData['answer'] = $request->input('answer');
				$setData['hotel_id'] = $request->input('hotel_id');
				if($request->input('row_id') > 0){
					self::$HotelFaqs->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
					$record = self::$HotelFaqs->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }
        }
    }


	#getHotelRules
    public function getHotelRules(Request $request){
		$validator = Validator::make($request->all(), [
			'rowId' => 'required|numeric', 
		], [
			'rowId.required' => 'Please enter rowId.'
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('title')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('rowId')));
				die;
			}
		}else{
			$records = self::$HotelRules->where('hotel_id',$request->rowId)->where('status','!=', 3)->get();
			return view('/panel/hotels/rules',compact(['records']));
		}
	}
	
	#getLandmarksRules
    public function getLandmarksRules(Request $request){
		$validator = Validator::make($request->all(), [
			'rowId' => 'required|numeric', 
		], [
			'rowId.required' => 'Please enter rowId.'
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('title')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('rowId')));
				die;
			}
		}else{
			$records = self::$HotelLandmarks->where('hotel_id',$request->rowId)->where('status','!=', 3)->get();
			return view('/panel/hotels/landmarks',compact(['records']));
		}
	}

	#add new faq
    public function addHotelRules(Request $request){
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'title' => 'required', 
				'description' => 'required', 
			], [
				'title.required' => 'Please enter title.',
				'description.required' => 'Please enter description.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));
                    die;
                }
				if($errors->first('description')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('description')));
                    die;
                }
            } else {
				$setData['title'] = $request->input('title');
                $setData['description'] = $request->input('description');
				$setData['hotel_id'] = $request->input('hotel_id');
				if($request->input('row_id') > 0){
					self::$HotelRules->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
					$record = self::$HotelRules->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }
        }
    }

	#add new faq
    public function addHotelLandmark(Request $request){
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'title' => 'required', 
				'description' => 'required', 
			], [
				'title.required' => 'Please enter title.',
				'description.required' => 'Please enter value.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));
                    die;
                }
				if($errors->first('description')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('description')));
                    die;
                }
            } else {
				$setData['category_id'] = $request->input('category_id');
				$setData['title'] = $request->input('title');
                $setData['description'] = $request->input('description');
				$setData['hotel_id'] = $request->input('hotel_id');
				
				# profile pic upload
				if(isset($request->icon) && $request->icon != 'undefined'  && $request->icon->extension() != ""){
					$validator = Validator::make($request->all(), [
						'icon' => 'required|image|mimes:jpeg,png,jpg|max:1048'
					]);
					if($validator->fails()){
						$errors = $validator->errors();
						return json_encode(array('heading'=>'Error','msg'=>$errors->first('banner')));die;
					}else{
						$actual_image_name = time().'.'.$request->icon->extension();
						$destination = base_path().'/public/img/landmarks/';
						$request->icon->move($destination, $actual_image_name);
                        $setData['icon'] = $actual_image_name;

						if($request->input('old_icon') != "" && $request->input('row_id') > 0){
							if(file_exists($destination.$request->input('old_icon'))){
								unlink($destination.$request->input('old_icon'));
							}
						}
					}
				}else{
                    if($request->input('row_id') > 0){
					    $actual_image_name = $request->input('old_icon');
                    }
				}

				if($request->input('row_id') > 0){
					self::$HotelLandmarks->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
					
					$setData['status'] = 1;
					$record = self::$HotelLandmarks->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }
        }
    }
}