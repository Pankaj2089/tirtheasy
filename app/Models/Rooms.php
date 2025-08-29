<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Rooms extends Authenticatable{

  use HasFactory, Notifiable;

	protected $table = 'rooms';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'title',
    'hotel_id',
    'amenities',
    'no_of_rooms',
    'price',
    'no_of_guest',
    'no_of_child',
    'cancellation_policy',
    'extra_mattress',
    'extra_mattress_price',
    'room_size',
    'image',
    'status',
    'no_of_single_rooms',
    'no_of_single_beds',
    'no_of_double_rooms',
    'no_of_double_beds',
    'no_of_guest_in_room',
    'no_of_child_in_room',
    'no_of_guest_in_double_room',
    'no_of_child_in_double_room'
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