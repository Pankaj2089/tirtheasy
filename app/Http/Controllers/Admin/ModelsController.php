<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Models;
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

class ModelsController extends Controller{

    private static $Models;
	private static $Brands;
    private static $TokenHelper;

    public function __construct(){
        self::$Models = new Models();
		self::$Brands = new Brands();
		self::$TokenHelper = new TokenHelper();
    }

    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
		$brands = self::$Brands->where('status', '!=', 3)->orderBy('title','ASC')->get();
        return view('/panel/models/index',compact('brands'));
    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){return redirect('/panel/');}

        $query = self::$Models->where('status', '!=', 3);

        if($request->input('search_title') && $request->input('search_title') != ""){

            $query->where('title', 'like', '%' . $request->input('search_title') . '%');

        }
		if($request->input('search_status') && $request->input('search_status') != ""){

            $query->where('status', $request->input('search_status'));

        }

		if($request->input('search_brand_id') && $request->input('search_brand_id') != ""){
		
			$query->where('brand_id', $request->input('search_brand_id'));
		
		}

        $records = $query->orderBy('id', 'DESC')->paginate(20);
		foreach($records as $key => $record){
			$brand = self::$Brands->where('id',$record->brand_id)->first();
			$record->brand_name = isset($brand->id) ? $brand->title : 'N/A';
		}
        return view('/panel/models/paginate', compact('records'));

    }

    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/panel/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'brand_id' => 'required', 
				'title' => 'required', 

			], [

				'brand_id.required' => 'Please select brand.',
				'title.required' => 'Please enter title.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('brand_id')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('brand_id')));

                    die;

                }
				if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

            } else {

				if($request->input('row_id') == 0){
					$ordering = 1;
					$maxOrdering = self::$Models->max('ordering');
					if($maxOrdering > 0){
						$ordering = $maxOrdering+1;
					}
					$setData['ordering'] = $ordering;
				}

				$setData['title'] = $request->input('title');
				$setData['brand_id'] = $request->input('brand_id');
				$setData['slug'] = Str::slug($request->input('title'));
				$setData['seo_title'] = $request->input('seo_title');
				$setData['seo_description'] = $request->input('seo_description');
				$setData['seo_keyword'] = $request->input('seo_keyword');
				if($request->input('row_id') > 0){
					self::$Models->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
					$record = self::$Models->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }

        }

        return view('/panel/models/add-page');

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
			$record = self::$Models->where('id',$request->rowId)->first();
			echo json_encode(array('heading' => 'Success', 'record' => $record));die;
		}
	}
}