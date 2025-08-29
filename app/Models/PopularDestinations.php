<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PopularDestinations extends Authenticatable{

    use HasFactory, Notifiable;
    protected $table = 'popular_destinations';
    protected $fillable = [
        'heading',
        'sub_heading',
        'text',
        'status',
        'image',
        'destination_type',
        'property_type'
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