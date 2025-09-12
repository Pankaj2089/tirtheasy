<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Orders;
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

class OrdersController extends Controller{

    private static $Orders;
    private static $TokenHelper;

    public function __construct(){
        self::$Orders = new Orders();
		self::$TokenHelper = new TokenHelper();
    }

    #admin dashboard page
    public function getList(Request $request){
        
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        return view('/panel/orders/index');

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
        $query = self::$Orders->where('invoice_id', '!=','');
        
        $SearchKeyword = $request->input('search_title');
        if(!empty($SearchKeyword)) {
            $query->where('invoice_id', 'like', '%'.$SearchKeyword.'%')
            ->orWhere('billing_name', 'like', '%'.$SearchKeyword.'%')
            ->orWhere('billing_email', $SearchKeyword)
            ->orWhere('billing_phone', $SearchKeyword);
        }
		// if($request->input('search_type') && $request->input('search_type') != ""){
        //     $query->where('type', 'like', '%' . $request->input('search_type') . '%');
        // }
        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/orders/paginate', compact('records'));

    }

    #view page
    public function viewOrderPage(Request $request,$row_id){
        if(!$request->session()->has('admin_email')){return redirect('/panel/');}
		if($row_id > 0){
			$record = self::$Orders->where('id',$row_id)->first();
			return view('/panel/orders/view-order-page',compact(['record']));
		}else{
			return redirect('/panel/orders');
		}
    }
}