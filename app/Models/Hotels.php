<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Hotels extends Authenticatable{

  use HasFactory, Notifiable;

	protected $table = 'hotel';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'title',
		'added_by',
		'slug',
		'hotel_type',
		'state',
		'city',
		'address',
		'amenities',
		'facilities',
		'check_in_time',
		'check_out_time',
		'policy',
		'latitude',
		'longitude',
		'description',
		'image',
		'seo_title',
		'seo_description',
		'seo_keyword',
		'robot_tags',
		'status',
		'browse_top',
		'price',
		'cancellation_policy',
		'special_note',
		'extra_bed_policy',
		'check_in_restrictions',
		'food_arrangement',
		'id_proof_related',
		'property_accessibility',
		'pet_related',
		'other_rules',
		'is_only_jain'
    ];

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