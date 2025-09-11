<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Contacts extends Authenticatable{
  use HasFactory, Notifiable;
	protected $table = 'contacts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'type',
      'name',
      'email',
      'contact',
      'subject',
      'message',
      'booking_number',
      'booking_total_guest',
      'booking_type',
      'destinations'
    ];

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