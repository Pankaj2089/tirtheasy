<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CouponCodes;
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

class CouponCodesController extends Controller{
	
	private static $CouponCodes;
    private static $TokenHelper;	
	public function __construct(){
		self::$CouponCodes = new CouponCodes();
        self::$TokenHelper = new TokenHelper();
	}

    #admin dashboard page
    public function getList(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        return view('/panel/coupon_codes/index');
    }
    public function listPaginate(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        $query = self::$CouponCodes->where('status', '!=', 3);
		 if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }
		$records =  $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/coupon_codes/paginate',compact('records'));
    }

    #add new Brand
    public function addPage(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
		if($request->input()){
			$validator = Validator::make($request->all(), [
                'title' => 'required',
				'discount_type' => 'required',
				'amount' => 'required',
				'start_date' => 'required',
				'end_date' => 'required',
            ],[
                'title.required' => 'Please enter title.',
				'discount_type.required' => 'Please enter discount type.',
				'amount.required' => 'Please enter amount.',
				'start_date.required' => 'Please enter start date.',
				'end_date.required' => 'Please enter end date.',
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('title')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('title')));die;
				}
				if($errors->first('discount_type')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('discount_type')));die;
				}
				if($errors->first('amount')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('amount')));die;
				}
				if($errors->first('start_date')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('start_date')));die;
				}
				if($errors->first('end_date')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('end_date')));die;
				}
			}else{
                if(!self::$CouponCodes->ExistingRecord($request->input('title'))){					
                    $setData['title'] = strtoupper($request->input('title'));
					$setData['discount_type'] = $request->input('discount_type');
					$setData['amount'] = $request->input('amount');
					$setData['start_date'] = $request->input('start_date');
					$setData['end_date'] = $request->input('end_date');
					$setData['start_date_str'] = strtotime($request->input('start_date').' 00:00:00');
					$setData['end_date_str'] = strtotime($request->input('end_date').' 23:59:59'); 
                    $record = self::$CouponCodes->CreateRecord($setData);
					echo json_encode(array('heading'=>'Success','msg'=>'Coupon code added successfully'));die;
                }else{
					echo json_encode(array('heading'=>'Error','msg'=>'Coupon already exists.'));die;				
				}
			}
		}		
		return view('/panel/coupon_codes/add-page');
    }

    #edit Brand
    public function editPage(Request $request, $row_id){
		$RowID =  base64_decode($row_id);
		if(!$request->session()->has('admin_email')){return redirect('/panel/');}

        if($request->input()){
			$validator = Validator::make($request->all(), [
                'title' => 'required',
				'discount_type' => 'required',
				'amount' => 'required',
				'start_date' => 'required',
				'end_date' => 'required',
            ],[
                'title.required' => 'Please enter title.',
				'discount_type.required' => 'Please enter discount type.',
				'amount.required' => 'Please enter amount.',
				'start_date.required' => 'Please enter start date.',
				'end_date.required' => 'Please enter end date.',
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('title')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('title')));die;
				}
				if($errors->first('discount_type')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('discount_type')));die;
				}
				if($errors->first('amount')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('amount')));die;
				}
				if($errors->first('start_date')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('start_date')));die;
				}
				if($errors->first('end_date')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('end_date')));die;
				}
			}else{
                //profile image
                if(self::$CouponCodes->ExistingRecordUpdate($request->input('title'), $RowID)){
                    echo json_encode(array('heading'=>'Error','msg'=>'Coupon already exists.'));die;
                }else{					
                    $setData['id'] =  $RowID;
                    $setData['title'] = strtoupper($request->input('title'));
					$setData['discount_type'] = $request->input('discount_type');
					$setData['amount'] = $request->input('amount');
					$setData['start_date'] = $request->input('start_date');
					$setData['end_date'] = $request->input('end_date');
					$setData['start_date_str'] = strtotime($request->input('start_date').' 00:00:00');
					$setData['end_date_str'] = strtotime($request->input('end_date').' 23:59:59');
                    self::$CouponCodes->UpdateRecord($setData);
                }
                echo json_encode(array('heading'=>'Success','msg'=>'Coupon code information updated successfully'));die;
			}
		}
		$rowData = self::$CouponCodes->where(array('id' => $RowID))->first();
        if(isset($rowData->id)){			
            return view('/panel/coupon_codes/edit-page',compact('rowData','row_id'));
        }else{
            return redirect('/panel/coupon-codes');
        }
    }

}