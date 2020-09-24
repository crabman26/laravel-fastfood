<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\User;

class EmailAvailable extends Controller
{
    //
    function index()
    {
     return view('main.email_available');
    }

    function check(Request $request){
    	if($request->get('username')){
	      $username = $request->get('username');
	      $data = DB::table("users")
	       ->where('name', $username)
	       ->count();
	      if($data > 0){
	       	echo 'not_unique';
	      } else{
       		echo 'unique';	
      		}
     	}
    }

    function register(Request $request){
		$validation = Validator::make($request->all(),[
        	'email' => 'required',
        	'name' => 'required',
        	'password' => 'required'
       		]);
       	$error_array = array();
       	$success_output = '';
		if ($validation->fails()){
		    foreach($validation->messages()->getMessages() as $field_name => $message){
		        $error_array[] = $message; 
		    }
		} else {
		    $user = new User([
		        'email' => $request->get('email'),
		        'name' => $request->get('name'),
		        'password' => $request->get('password')
		    ]);
		    $user->save();
	       	$success_output = 'User registered succesfully!';
	    }

    }


}
