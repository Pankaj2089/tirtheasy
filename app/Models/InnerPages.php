<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class InnerPages extends Authenticatable{
  use HasFactory, Notifiable;
	protected $table = 'inner_pages';
    protected $fillable = [
		'title',
		'description',
		'seo_title',
		'seo_description',
		'seo_keyword',
		'image',
		'status'
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
    public function ExistingRecord($title){
		return $this::where('title',$title)->where('status','!=', 3)->exists();
	}
	public function ExistingRecordUpdate($title, $id){
		return $this::where('title',$title)->where('id','!=', $id)->where('status','!=', 3)->exists();
	}
}