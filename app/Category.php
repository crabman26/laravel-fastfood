<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = ['Name'];

    public function product(){
    	return $this->hasOne('App\Product');
    }

    public function offers(){
    	return $this->hasMany('App\Offer');
    }
}
