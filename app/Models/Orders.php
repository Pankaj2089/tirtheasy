<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Orders extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		"user_id",
        "invoice_id",
        "hotel_id",
        "room_id",
        "room_price_id",
        "rooms",
        "adults",
        "childs",
        "txn_id",
        "sub_total",
        "discount",
        "convenience",
        "coupon_code",
		"cod_amount",
        "grand_total",
        "billing_name",
        "billing_email",
        "billing_phone",
        "billing_address",
        "billing_city",
        "billing_state",
        "payment_status",
        "check_in_date",
        "check_out_date",
        "order_platform",
        "payment_method_type",
		"other_details",
        "extra_mattress",
        "extra_mattress_price",
        "in_no"
    ];

    protected $UpdatableFields = [ ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
	
	public function GetRecordById($id){
		return $this::where('id', $id)->first();
	}
	public function UpdateRecord($Details){
		$Record = $this::where('id', $Details['id'])->update($Details);
		return true;
	}
	public function CreateRecord($Details){
		$Record = $this::create($Details);
		return $Record;
	}


}