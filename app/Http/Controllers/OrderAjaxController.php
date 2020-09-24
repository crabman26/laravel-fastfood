<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Order;
use Validator;
use DataTables;
use Mail;

class OrderAjaxController extends Controller
{
    //
    function index(){
    	return view('order.index');
    }

    function getdata(Request $request){
        if($request->ajax()){
            if ($request->username){
                $orders = DB::table('orders')
                ->join('users','orders.user_id','=','users.id')
                ->join('products','orders.product_id','=','products.id')
                ->select('orders.id','users.name','products.Title','orders.FullName','orders.Adress','orders.Phone','orders.State','orders.Status')
                ->where('users.name',$request->username);
            } else if ($request->product){
                $orders = DB::table('orders')
                ->join('products','orders.product_id','=','products.id')
                ->join('users','orders.user_id','=','users.id')
                ->select('orders.id','users.name','products.Title','orders.FullName','orders.Adress','orders.Phone','orders.State','orders.Status')
                ->where('products.Title',$request->product);
            } else if ($request->status){
                $orders = DB::table('orders')
                ->join('users','orders.user_id','=','users.id')
                ->join('products','orders.product_id','=','products.id')
                ->select('orders.id','users.name','products.Title','orders.FullName','orders.Adress','orders.Phone','orders.State','orders.Status')
                ->where('orders.Status',$request->status);
            } else {
                $orders = DB::table('orders')
                ->join('users','orders.user_id','=','users.id')
                ->join('products','orders.product_id','=','products.id')
                ->select('orders.id','users.name','products.Title','orders.FullName','orders.Adress','orders.Phone','orders.State','orders.Status');
            }
        }
    	
    	return DataTables::of($orders)
    		->addColumn('action',function($order){
    			return '<a href="#" id="'.$order->id.'" class="btn btn-xs btn-primary edit"><i class="glyphicon glyphicon-edit"></i>Edit</a>
    					<a href="#" id="'.$order->id.'" class="btn btn-xs btn-danger delete"><i class="glyphicon glyphicon-remove"></i>Delete</a>';
    		})
    		->addColumn('checkbox','<input type="checkbox" name="order_checkbox" class="order_checkbox" value="{{$id}}"/>')
    		->rawcolumns(['checkbox','action'])
    		->make(true);
    }

    function memberorder(Request $request){
        if($request->ajax()){
            $orders = DB::table('orders')
            ->join('products','orders.product_id','=','products.id')
            ->join('users','orders.user_id','=','users.id')
            ->select('orders.id','products.Title','orders.price','orders.FullName','orders.Adress','orders.Phone','orders.State','orders.Status')
            ->where('user_id',$request->userid);
        }
        
        return DataTables::of($orders)
            ->addColumn('action',function($order){
                return '<a href="#" id="'.$order->id.'" class="btn btn-xs btn-primary edit"><i class="glyphicon glyphicon-edit"></i>Edit</a>
                        <a href="#" id="'.$order->id.'" class="btn btn-xs btn-danger delete"><i class="glyphicon glyphicon-remove"></i>Cancel</a>';
            })
            ->make(true);
    }


    function postdata(Request $request){
    	$validation = Validator::make($request->all(),
        [
    		'User' => 'required',
    		'Product' => 'required',
    		'FullName' => 'required',
    		'Adress' => 'required',
    		'Phone' => 'required'
    	]);

        $error_array = array();
    	$success_output = '';
    	if ($validation->fails()){
    		foreach($validation->messages()->getmessages() as $field_name => $messages){
    			$error_array[] = $messages;
    		}
    	} else {
    		$product = $request->get('Product'); 
            $username = $request->get('User');
    		$pid = DB::table('products')->where('Title',$product)->value('id');
            $price = DB::table('products')->where('Title',$product)->value('price');
            $uid = DB::table('users')->where('name',$username)->value('id');
    		if ($request->get('button_action') == 'insert'){
	    		$order = new Order([
	    			'user_id' => $uid,
	    			'product_id' => $pid,
                    'price' => $price,
	    			'FullName' => $request->get('FullName'),
	    			'Adress' => $request->get('Adress'),
	    			'Phone' => $request->get('Phone'),
	    			'State' => $request->get('State'),
	    			'Status' => $request->get('Status')
	    		]);
	    		$order->save();
	    		$success_output = '<div class="alert alert-success">Order inserted succesfully.</div>';
    		} if ($request->get('button_action') == 'update'){
    			$order = Order::find($request->get('order_id'));
    			$order->user_id = $uid;
    			$order->product_id = $pid;
    			$order->FullName = $request->get('FullName');
    			$order->Adress = $request->get('Adress');
    			$order->Phone = $request->get('Phone');
    			$order->State = $request->get('State');
    			$order->Status = $request->get('Status');
    			$order->save();
    			$success_output = '<div class="alert alert-success">Order updated succesfully.</div>';
    		} 

    	}
    	$output = array(
    		'error' => $error_array,
    		'success' => $success_output
    	);

    	echo json_encode($output);
    }

    function orderuser(Request $request){
        $validation = Validator::make($request->all(),
        [
            'Username' => 'required',
            'FullName' => 'required',
            'Adress' => 'required',
            'Phone' => 'required',
            'State' => 'required'
        ]);

        $error_array = array();
        $success_output = '';
        if ($validation->fails()){
            foreach($validation->messages()->getmessages() as $field_name=>$messages){
                $error_array[] = $messages;
            }
        } else {
            if ($request->get('button_action') == 'user_order'){
                    $username = $request->get('Username');
                    $uid = DB::table('users')->where('name',$username)->value('id');
                    $price = DB::table('products')->where('id',$request->get('pid'))->value('price');
                    $order = new Order([
                        'user_id' => $uid,
                        'product_id' => $request->get('pid'),
                        'price' => $price,
                        'FullName' => $request->get('FullName'),
                        'Adress' => $request->get('Adress'),
                        'Phone' => $request->get('Phone'),
                        'State' => $request->get('State'),
                        'Status' => 'Preparation'
                    ]);
                    $order->save();
                    $success_output = '<div class="alert alert-success">Order inserted succesfully.</div>';
            } if ($request->get('button_action') == 'update'){
                $order = Order::find($request->get('order_id'));
                $pid = DB::table('products')->where('Title',$request->get('Product'))->value('id');
                $order->product_id = $pid;
                $order->FullName = $request->get('FullName');
                $order->Adress = $request->get('Adress');
                $order->Phone = $request->get('Phone');
                $order->State = $request->get('State');
                $order->save();
                $success_output = '<div class="alert alert-success">Order updated succesfully.</div>';
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
    	$order = Order::find($id);
        $uid = $order->user_id;
        $pid = $order->product_id;
        $username = DB::table('users')->where('id',$uid)->value('name');
        $product = DB::table('products')->where('id',$pid)->value('Title');
    	$output = array(
    		'User' => $username,
    		'Product' => $product,
    		'FullName' => $order->FullName,
    		'Adress' => $order->Adress,
    		'Phone' => $order->Phone,
    		'State' => $order->State,
    		'Status' => $order->Status
    	);

    	echo json_encode($output);
    }

    function cancelorder(Request $request){
        $id = $request->input('id');
        $order = Order::find($id);
        $order->Status = 'Cancellation';
        $order->save();
        $success_output = 'Order cancelled succesfully.';
        echo json_encode($success_output);
        // Mail::send('member.index',
        // array('Message' => 'User cancelled order with id='.$id.
        // ), 
        // function($message){
        //     $message->from('info@tipota.com');
        //     $message->to('admin@tipota.com')->subject('User order cancellation.');
        // });    
    }

    function removedata(Request $request){
    	$id = $request->input('id');
    	$order = Order::find($id);
    	if ($order->delete()){
    		echo "Data deleted";
    	}
    }

    function massremove(Request $request){
    	$id = $request->input('id');
    	$order = Order::WhereIn('id',$id);
    	if ($order->delete()){
    		echo "Data deleted";
    	}
    }

    function getUsers(){
        $users = DB::table('users')
                ->groupBy('name')
                ->get();

        echo json_encode($users);
    }



    
}
