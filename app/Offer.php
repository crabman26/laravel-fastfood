<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    //
    protected $fillable = ['cat_id','Title','Discount','Begin_Date','Expire_Date','Active'];

    public function Categories(){
    	return $this->belongsTo('App\Category');
    }
}
