<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['cat_id','Title','Price','Description','Available'];

    public function Category(){
    	return $this->belongsTo('App\Category');
    }

    public function Orders(){
    	return $this->belongsTo('App\Order');
    }
}
