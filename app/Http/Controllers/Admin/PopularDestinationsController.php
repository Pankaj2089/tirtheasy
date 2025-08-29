<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PopularDestinations;
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
class PopularDestinationsController extends Controller{
    private static $PopularDestinations;
    private static $TokenHelper;
    public function __construct(){
        self::$PopularDestinations = new PopularDestinations();
        self::$TokenHelper = new TokenHelper();
    }
	
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        return view('/panel/popular-destinations/index');
    }
	
    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $query = self::$PopularDestinations->where('status', '!=', 3);
        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('heading', 'like', '%' . $request->input('search_title') . '%');
        }
        if($request->input('search_status') && $request->input('search_status') != ""){
            $status = $request->input('search_status');
            $query->where('status', 'like', '%'.$status.'%');
        }
        if($request->input('destination_type') && $request->input('destination_type') != ""){
            $status = $request->input('destination_type');
            $query->where('destination_type', $status);
        }
        if($request->input('property_type') && $request->input('property_type') != ""){
            $status = $request->input('property_type');
            $query->where('property_type', $status);
        }
        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);
        return view('/panel/popular-destinations/paginate', compact('records'));
    }
	
    #add new Service Type
    public function addPage(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        if($request->input()){
            $validator = Validator::make($request->all(), [
                'heading' => 'required',
				'image' => 'required', 
			], [
                'heading.required' => 'Please enter popular destination title.',
				'image.required' => 'Please enter iopular destination image.',
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('heading')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('heading')));
                    die;
                }
                if($errors->first('image')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));
                    die;
                }
            }else{
                if(isset($request->image) && $request->image->extension() != ""){
                    $validator = Validator::make($request->all(), [
						'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'
					]);
                    if($validator->fails()){
                        $errors = $validator->errors();
                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));
                        die;
                    }else{
                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('image')).'.'.$request->image->extension());
                        $destination = base_path().'/public/img/popular-destinations/';
                        $request->image->move($destination, $actual_image_name);
                        $setData['image'] = $actual_image_name;
                    }
                }
                $setData['heading'] = $request->input('heading');
                $setData['sub_heading'] = $request->input('sub_heading');
                $setData['text'] = $request->input('text');
                $setData['destination_type'] = $request->input('destination_type');
                $setData['property_type'] = $request->input('property_type');
                $record = self::$PopularDestinations->CreateRecord($setData);
                echo json_encode(array('heading' => 'Success', 'msg' => 'Popular destination added successfully'));
                die;
            }
        }
        return view('/panel/popular-destinations/add-page');
    }
	
    #edit Service Type
    public function editPage(Request $request, $row_id){
        $RowID = $row_id;
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        $rowData = self::$PopularDestinations->where(array('id' => $RowID))->first();
        if($request->input()){
            if(isset($request->image) && $request->image->extension() != ""){
                 $validator = Validator::make($request->all(), [
                'heading' => 'required',
				'image' => 'required', 
                ], [
                    'heading.required' => 'Please enter popular destination title.',
                    'image.required' => 'Please enter iopular destination image.',
                ]);
                if($validator->fails()){
                    $errors = $validator->errors();
                    if($errors->first('heading')){
                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('heading')));
                        die;
                    }
                    if($errors->first('image')){
                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));
                        die;
                    }
                }else{
                    $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('image')).'.'.$request->image->extension());
                    $destination = base_path().'/public/img/popular-destinations/';
                    $request->image->move($destination, $actual_image_name);
                    $setData['image'] = $actual_image_name;
                    if($rowData->image != ""){
                        if(file_exists($destination.$rowData->image)){
                            unlink($destination.$rowData->image);
                        }
                    }
                }
            }
            $setData['id'] = $RowID;
            $setData['sub_heading'] = $request->input('sub_heading');
            $setData['heading'] = $request->input('heading');
            $setData['text'] = $request->input('text');
            $setData['destination_type'] = $request->input('destination_type');
            $setData['property_type'] = $request->input('property_type');
            self::$PopularDestinations->UpdateRecord($setData);        
            echo json_encode(array('heading' => 'Success', 'msg' => 'Popular destination updated successfully'));
            die;
        }
        if(isset($rowData->id)){
            return view('/panel/popular-destinations/edit-page', compact('rowData', 'row_id'));
        }else{
            return redirect('/panel/popular-destinations');
        }
    }
	
}