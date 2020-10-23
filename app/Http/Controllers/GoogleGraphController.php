<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class GoogleGraphController extends Controller
{
    //
    function index(){
    	$productdata = DB::table('orders')
    		->join('products','orders.product_id','=','products.id')
    		->select(
    			DB::raw('products.Title as Title'),
    			DB::raw('count(*) as quantity')
    		)
    		->groupBy('products.Title')
    		->get();

    	$array[] = ['Title','Quantity'];

    	foreach($productdata as $key => $value){
    		$array[++$key] = [$value->Title, $value->quantity];
    	}

    	$statedata = DB::table('orders')
    		->select(
    			DB::raw('State as State'),
    			DB::raw('count(*) as number')
    		)
    		->groupBy('State')
    		->get();

    	$state[] = ['State','Number'];

    	foreach($statedata as $key => $value){
    		$state[++$key] = [$value->State, $value->number];
    	}

    	$statusdata = DB::table('orders')
    		->select(
    			DB::raw('Status as Status'),
    			DB::raw('count(*) as percentage')
    		)
    		->groupBy('Status')
    		->get();

    	$status[] = ['Status','Percentage'];

    	foreach($statusdata as $key => $value){
    		$status[++$key] = [$value->Status, $value->percentage];
    	}

    	return view('graph.productchart')
    	->with('Title',json_encode($array))
    	->with('State',json_encode($state))
    	->with('Status',json_encode($status));
    }
}
