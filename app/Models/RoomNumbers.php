<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class RoomNumbers extends Authenticatable{

  use HasFactory, Notifiable;

	protected $table = 'room_numbers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'title',
    'hotel_id',
    'room_id',
    'prefix',
    'full_title',
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