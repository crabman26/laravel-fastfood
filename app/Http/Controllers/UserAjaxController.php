<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use App\User;
use DataTables;
use Validator;

class UserAjaxController extends Controller
{
    //

    function index(){
    	return view('user.index');
    }

    function getdata(Request $request){
    	$users = DB::table('users')
    			->select('id','name','email','Role')
    			->get();

    	return DataTables::of($users)
    		->addColumn('action',function($user){
    			return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$user->id.'"><i class="glyphicon glyphicon-edit"></i> Update</a><a href="#" class="btn btn-xs btn-danger delete" id="'.$user->id.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
    		})
    		->addColumn('checkbox', '<input type="checkbox" name="user_checkbox[]" class="user_checkbox" value="{{$id}}" />')
            ->rawColumns(['checkbox','action'])
    		->make(true);
    }

    function memberdata(Request $request){
    	if ($request->ajax()){
	    	$member = DB::table('users')
	    			->select('id','name','email')
	    			->where('email',$request->mail)
	    			->get();
	    	return DataTables::of($member)
	    		->addColumn('action',function($member){
	    			return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$member->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
	    		})
	    		->make(true);

    	}
    }

    function postdata(Request $request){
    	$validation = Validator::make($request->all(),[
    		'Name' => 'required',
    		'E-mail' => 'required',
    		'Password' => 'required'
    	]);
    	$error_array = array();
    	$success_output = '';
    	if ($validation->fails()){
    		foreach($validation->messages()->getMessages() as $field_name=>$messages){
    			$error_array[] = $messages;
    		}
    	} else {
    		if ($request->get('button_action') == 'Insert'){
	    		$user = new User([
	    			'name' => $request->get('Name'),
	    			'email' => $request->get('E-mail'),
	    			'password' => Hash::make($request->get('Password')),
	    			'Role' => $request->get('Role')
	    		]);
	    		$user->save();
	    		$success_output = '<div class="alert alert-success>User inserted succesfully.</div>';

    		} else if ($request->get('button_action') == 'Update'){
    			$id = $request->get('user_id');
    			$user = User::find($id);
    			$user->name = $request->get('Name');
    			$user->email = $request->get('E-mail');
    			$user->password = Hash::make($request->get('Password'));
    			$user->Role = $request->get('Role');
    			$user->save();
    			$success_output = '<div class="alert alert-success>User updated succesfully.</div>';
    		}

    	}
    	
        $output = array(
    		'error' => $error_array,
    		'success' => $success_output
    	);

    	echo json_encode($output);
    }

    function fetchdata(Request $request){
    	$id = $request->input('id');
    	$user = User::find($id);
    	$output = array(
    		'Name' => $user->name,
    		'Email' => $user->email,
    		'Password' => $user->password,
    		'Role' => $user->Role,
    	);

    	echo json_encode($output);

    }

    function removedata(Request $request){
    	$id = $request->input('id');
    	$user = User::find($id);
    	if ($user->delete()){
    		echo "User deleted succesfully.";
    	}
    }

    function massremove(Request $request){
    	$user_id_array = $request->input('id');
    	$user = User::WhereIn('id',$user_id_array);
    	if ($user->delete()){
    		echo "Users deleted succesfully.";
    	}
    }

    function userid(Request $request){
        $mail = $request->input('mail');
        $user = DB::table('users')->where('email',$mail)->value('id');
        
        echo json_encode($user);
    }
}
