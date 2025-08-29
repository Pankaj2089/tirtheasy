<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
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
use App\Models\State;
class AjaxController extends Controller
{
    private static $TokenHelper;
	public function __construct(){
        self::$TokenHelper = new TokenHelper();
	}
	
	public function getCities(Request $request){
		
		$html = '<option value="">Select</option>';
		$statId = $request->input('stateId');
		$records = DB::table('cities')->where('state_id', $statId)->where('status', 1)->orderBy('title', 'ASC')->get();
		if(count($records) > 0){
			foreach($records as $record){
				$html .= '<option value="'.$record->title.'">'.$record->title.'</option>';
			}
		}
		echo $html;die;
	}
	public function changePrice(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$value = $request->input('value');
		$rowID = $request->input('id');
		$field = $request->input('field');
		if(DB::table('products')->where(array('id' => $rowID))->update(array($field => $value))){
			echo "Success"; die;
		}else{
			echo "Error"; die;
		}
	}
	
	public function changeProductStatus(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$tableName = $request->input('table');
		$rowID = $request->input('rowID');
		$new_status = $request->input('status');
		
		$record = DB::table($tableName)->select('status')->where(array('id' => $rowID))->first();
		$status = $record->status;
		if($tableName != "" && $rowID != "" && $status != "" && is_numeric($rowID) && is_numeric($status)){            
            $newStatus = $new_status;
			if($newStatus == 3){
				DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => $newStatus, 'moderation_status' => 'Pending'));
			}else{
				DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => $newStatus));
			}			
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
    }
	
	public function changeBrowseTopStatus(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$tableName = $request->input('table');
		$rowID = $request->input('rowID');
		$status = $request->input('status');
		if($tableName != "" && $rowID != "" && $status != "" && is_numeric($rowID) && is_numeric($status)){

            $newStatus = $status == 1 ? 2 : 1;
			DB::table($tableName)->where(array('id' => $rowID))->update(array('browse_top' => $newStatus));
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
    }
    public function changeStatus(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$tableName = $request->input('table');
		$rowID = $request->input('rowID');
		$status = $request->input('status');
		if($tableName != "" && $rowID != "" && $status != "" && is_numeric($rowID) && is_numeric($status)){

			if($tableName == 'states' && $status == 1){
				$stateUsed = DB::table('cities')->where('state_id', $rowID)->where('status', 1)->count();
				if($stateUsed > 0){
					echo 'Cannot in-active this record (One or more cities are associated with this state.)';die;
				}
			}

			if($tableName == 'amenity_categories' && $status == 1){
				$stateUsed = DB::table('amenities')->where('category_id', $rowID)->where('status', 1)->count();
				if($stateUsed > 0){
					echo 'Cannot in-active this record (One or more amenities are associated with this category.)';die;
				}
			}

            $newStatus = $status == 1 ? 2 : 1;
			DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => $newStatus));
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
    }
    public function deleteRecord(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$tableName = $request->input('table');
		$rowID = $request->input('rowID');
		if($tableName != "" && $rowID != "" && is_numeric($rowID)){
			if($tableName == 'states' ){
				$stateUsed = DB::table('cities')->where('state_id', $rowID)->where('status','!=', 3)->count();
				if($stateUsed > 0){
					echo 'Cannot delete this record (One or more cities are associated with this state.)';die;
				}
			}

			if($tableName == 'amenity_categories'){
				$recordsed = DB::table('amenities')->where('category_id', $rowID)->where('status','!=', 3)->count();
				if($recordsed > 0){
					echo 'Cannot delete this record (One or more amenities are associated with this category.)';die;
				}
			}

            DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => 3));
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
    }
    public function productsChangeStatus(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$productIDs = $request->input('productIDs');
		$status = $request->input('status');
        if(count($productIDs) == 0){
            echo 'Please select Products.';die;
        }
		if($status != "" && is_numeric($status)){
            foreach($productIDs as $rowID){
                $newStatus = $status == 1 ? 2 : 1;
                DB::table('products')->where(array('id' => $rowID))->update(array('status' => $newStatus));
            }
			echo 'Success';die;
		}else{
			echo 'InvalidData'; die;
		}
    }
    public function productsDeleteRecord(Request $request){
		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}
		$productIDs = $request->input('productIDs');
		$status = $request->input('status');
        if(count($productIDs) == 0){
            echo 'Please select Products.';die;
        }
        foreach($productIDs as $rowID){
            $newStatus = $status == 1 ? 2 : 1;
            //DB::table('products')->where('id', $rowID)->delete();
            DB::table('products')->where(array('id' => $rowID))->update(array('status' => 3));
        }
        echo 'Success';die;
    }
	public function getLicenseByLicenseType(Request $request){
		$licenses = self::$Licenses->where('status',1)->where('license_type_id',$request->input('license_type_id'))->get();
		return view('/panel/ajax/license',compact('licenses'));
	}
    public function getHospitalDepartments(Request $request){
		$HospitalDepartments = self::$HospitalDepartments->where('status',1)->where('hospital_id',$request->input('hospital_id'))->get();
		return view('/panel/ajax/hospital_departments',compact('HospitalDepartments'));
	}
	
	
	public function getState(Request $request){
		if($request->ajax()){
			$country_id = $request->input('countryId');
			$states = DB::table('states')->where('country_id',$country_id)->where('status',1)->orderBy('state')->pluck('state','id');
			echo view('/panel/ajax/get_state',compact('states'));
		}
		exit;
	}
	
	public function getCity(Request $request){
		if($request->ajax()){
			$state_id = $request->input('stateId');
			$cities = DB::table('cities')->where('state_id',$state_id)->where('status',1)->orderBy('city')->pluck('city','id');
			echo view('/panel/ajax/get_city',compact('cities'));
		}
		exit;
	}
	
	#create Image
    function createImage($base64_string, $output_file, $ext = NULL) {
        if($ext == 'jpg'){
            $image = imagecreatefrompng($base64_string);
            imagejpeg($image, $output_file, 100);
            imagedestroy($image);
        }else{
            $ifp = fopen($output_file, "wb");
            $data = explode(',', $base64_string);
            fwrite($ifp, base64_decode($data[1]));
            fclose($ifp);
        }
    }
	
	public function uploadCropperImage(Request $request){
		if($request->ajax()){
			$msg = "Error";
			$postData = $request->all();
            if(!empty($postData)){
				$folder = $postData['folder'];
				$ext = $postData['ext'];
				$crop_img = $postData['crop_img'];
				$destination = base_path().'/public/admin/images/'.$folder.'/';
				$actual_image_name = time().random_int(0,99999).".".$ext;
				$this->createImage($crop_img,$destination.$actual_image_name,$ext);
				$fullpath = env('APP_URL').'/public/admin/images/'.$folder.'/'.$actual_image_name;				
				if($postData['old_image'] != ''){
					if(file_exists($destination.$postData['old_image'])){
						unlink($destination.$postData['old_image']);
					}
				}
            	echo json_encode(array('filename' => $actual_image_name, 'full_path' => $fullpath));
        	}
        	exit;
		}
	}
}
