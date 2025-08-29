<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserAccessCode extends Authenticatable{

    use HasFactory, Notifiable;
    protected $table = 'user_access_code';
    protected $fillable = [
        'mobile',
        'otp'
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