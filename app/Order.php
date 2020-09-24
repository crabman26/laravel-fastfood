<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['user_id','product_id','price','FullName','Adress','Phone','State','Status'];

    public function Users(){
    	return $this->belongsTo('App\User');
    }

    public function Products(){
    	return $this->belongsTo('App\Product');
    }
}
