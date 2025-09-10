<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Faqs;
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

class FaqsController extends Controller{
	
    private static $Faqs;
    private static $TokenHelper;
    public function __construct(){
        self::$Faqs = new Faqs();
		self::$TokenHelper = new TokenHelper();
    }
    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        return view('/panel/faqs/index');
    }
    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
         $query = self::$Faqs->where('status', '!=', 3);
		if($request->input('search_status') && $request->input('search_status') != ""){
            $query->where('faqs.status', $request->input('search_status'));
        }
        if($request->input('search_category') && $request->input('search_category') != ""){
            $query->where('faqs.category', $request->input('search_category'));
        }
        $records = $query->orderBy('faqs.id', 'DESC')->paginate(20);
        return view('/panel/faqs/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request){
        if(!$request->session()->has('admin_email')){
            return redirect('/panel/');
        }
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'category' => 'required', 
				'question' => 'required',  
				'answer' => 'required', 
			], [
                'category.required' => 'Please select category.',
				'question.required' => 'Please enter question.',
				'answer.required' => 'Please enter answer.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('category')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('category')));
                    die;
                }
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
                $setData['category'] = $request->input('category');
                $setData['answer'] = $request->input('answer');

				if($request->input('row_id') > 0){
					self::$Faqs->where('id',$request->row_id)->update($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;
				}else{
                    $setData['status'] = 1;
					$record = self::$Faqs->CreateRecord($setData);
					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;
				}
				
            }
        }
        return view('/panel/faqs/add-page');
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
			if($errors->first('rowId')){
				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('rowId')));
				die;
			}
		}else{
			$record = self::$Faqs->where('id',$request->rowId)->first();
            $record->icon_image = "";
            if(!empty($record->icon)){
                $record->icon_image = getenv('APP_URL').'/public/img/faqs/'.$record->icon;
            }
			echo json_encode(array('heading' => 'Success', 'record' => $record));die;
		}
	}
}