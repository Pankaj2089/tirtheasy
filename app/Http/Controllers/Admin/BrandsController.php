<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Brands;
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

class BrandsController extends Controller{

    private static $Brands;
    private static $TokenHelper;

    public function __construct(){
        self::$Brands = new Brands();
		self::$TokenHelper = new TokenHelper();
    }

    #admin dashboard page
    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        return view('/panel/brands/index');

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        $query = self::$Brands->where('status', '!=', 3);

        if($request->input('search_title') && $request->input('search_title') != ""){
            $query->where('title', 'like', '%' . $request->input('search_title') . '%');
        }
		if($request->input('search_type') && $request->input('search_type') != ""){
            $query->where('type', 'like', '%' . $request->input('search_type') . '%');
        }
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('status', $request->input('search_status'));
        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/brands/paginate', compact('records'));

    }

    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_email')){return redirect('/panel/');}

        if($request->input()){

            $validator = Validator::make($request->all(), [
				'title' => 'required', 
				'type' => 'required', 
			], [
				'title.required' => 'Please enter title.',
				'type.required' => 'Please enter type.'
			]);

            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('title')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;}
				if($errors->first('type')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('type')));die;}

            } else {

				if($request->input('row_id') == 0){
					$ordering = 1;
					$maxOrdering = self::$Brands->max('ordering');
					if($maxOrdering > 0){
						$ordering = $maxOrdering+1;
					}
					$setData['ordering'] = $ordering;
				}
				
				# profile pic upload
				if(isset($request->banner) && $request->banner->extension() != ""){
					$validator = Validator::make($request->all(), [
						'banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
					]);
					if($validator->fails()){
						$errors = $validator->errors();
						return json_encode(array('heading'=>'Error','msg'=>$errors->first('banner')));die;
					}else{
						$actual_image_name = time().'.'.$request->banner->extension();
						$destination = base_path().'/public/admin/images/brands/';
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
				$setData['type'] = $request->input('type');
				$setData['slug'] = Str::slug($request->input('title'));
				$setData['banner'] = $actual_image_name;
				$setData['seo_title'] = $request->input('seo_title');
				$setData['seo_description'] = $request->input('seo_description');
				$setData['seo_keyword'] = $request->input('seo_keyword');
				if($request->input('row_id') > 0){
					self::$Brands->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
					$record = self::$Brands->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }

        }

        return view('/panel/brands/add-page');

    }

    #getPage
    public function getPage(Request $request){
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
			$record = self::$Brands->where('id',$request->rowId)->first();
			echo json_encode(array('heading' => 'Success', 'record' => $record));die;
		}
	}
}