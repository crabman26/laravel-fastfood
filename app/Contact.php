<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    protected $fillable = ['Name','Surname','E-mail','Phone','Message']; 
}
