<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Promotions;
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
class PromotionsController extends Controller{
    private static $Promotions;
    private static $TokenHelper;
    public function __construct(){
        self::$Promotions = new Promotions();
        self::$TokenHelper = new TokenHelper();
    }
	
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        return view('/panel/promotions/index');
    }
	
    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $query = self::$Promotions->where('status', '!=', 3);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('heading', 'like', '%' . $request->input('search_title') . '%');
        }
        if($request->input('search_status') && $request->input('search_status') != ""){
            $status = $request->input('search_status');
            $query->where('status', 'like', '%'.$status.'%');
        }
        if($request->input('promotion_type') && $request->input('promotion_type') != ""){
            $status = $request->input('promotion_type');
            $query->where('promotion_type', 'like', '%'.$status.'%');
        }
        if($request->input('promotion_for') && $request->input('promotion_for') != ""){
            $status = $request->input('promotion_for');
            $query->where('promotion_for', $status);
        }
        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);
        return view('/panel/promotions/paginate', compact('records'));
    }
	
    #add new Service Type
    public function addPage(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'banner' => 'required', 
			], [
				'banner.required' => 'Please enter banner.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('banner')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('banner')));
                    die;
                }
            }else{
                if(isset($request->banner) && $request->banner->extension() != ""){
                    $validator = Validator::make($request->all(), [
						'banner' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'
					]);
                    if($validator->fails()){
                        $errors = $validator->errors();
                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('banner')));
                        die;
                    }else{
                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('banner')).'.'.$request->banner->extension());
                        $destination = base_path().'/public/img/promotions/';
                        $request->banner->move($destination, $actual_image_name);
                        $setData['banner'] = $actual_image_name;
                    }
                }
                $setData['heading'] = $request->input('heading');
                $setData['sub_heading'] = $request->input('sub_heading');
                $setData['text'] = $request->input('text');
                $setData['promotion_type'] = $request->input('promotion_type');
                $setData['promotion_for'] = $request->input('promotion_for');
                $record = self::$Promotions->CreateRecord($setData);
                echo json_encode(array('heading' => 'Success', 'msg' => 'Promotion added successfully'));
                die;
            }
        }
        return view('/panel/promotions/add-page');
    }
	
    #edit Service Type
    public function editPage(Request $request, $row_id){
        $RowID = $row_id;
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $rowData = self::$Promotions->where(array('id' => $RowID))->first();
        if($request->input()){
            if(isset($request->banner) && $request->banner->extension() != ""){
                $validator = Validator::make($request->all(), [
                    'banner' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'
                ]);
                if($validator->fails()){
                    $errors = $validator->errors();
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('banner')));
                    die;
                }else{
                    $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('banner')).'.'.$request->banner->extension());
                    $destination = base_path().'/public/img/promotions/';
                    $request->banner->move($destination, $actual_image_name);
                    $setData['banner'] = $actual_image_name;
                    if($rowData->banner != ""){
                        if(file_exists($destination.$rowData->banner)){
                            unlink($destination.$rowData->banner);
                        }
                    }
                }
            }
            $setData['id'] = $RowID;
            $setData['sub_heading'] = $request->input('sub_heading');
            $setData['heading'] = $request->input('heading');
            $setData['text'] = $request->input('text');
            $setData['promotion_type'] = $request->input('promotion_type');
            $setData['promotion_for'] = $request->input('promotion_for');
            self::$Promotions->UpdateRecord($setData);        
            echo json_encode(array('heading' => 'Success', 'msg' => 'Promotion updated successfully'));
            die;
        }
        if(isset($rowData->id)){
            return view('/panel/promotions/edit-page', compact('rowData', 'row_id'));
        }else{
            return redirect('/panel/promotions');
        }
    }
	
}