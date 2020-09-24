<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Offer;
use Validator;
use DataTables;

class OfferAjaxController extends Controller
{
    //
    function index(){
    	return view('offer.index');
    }

    function getdata(Request $request){
        if ($request->ajax()){
            if ($request->category){
                $offers = DB::table('offers')
                ->join('categories','categories.id','=','offers.cat_id')
                ->select('offers.id','categories.Name','offers.Title','offers.discount','offers.Begin_Date','offers.Expire_Date')
                ->where('categories.Name',$request->category);
            } else {
        	$offers = DB::table('offers')
            ->join('categories','categories.id','=','offers.cat_id')
        	->select('offers.id','categories.Name','offers.Title','offers.discount','offers.Begin_Date','offers.Expire_Date');
            }
        }
    	return DataTables::of($offers)
    	->addcolumn('action',function($offer){
    		return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$offer->id.'"><i class="glyphicon glyphicon-edit"></i>Edit</a>
    		        <a href="#" class="btn btn-xs btn-danger delete" id="'.$offer->id.'"><i class="glyphicon glyphicon-remove"></i>Delete</a>';
    	})
    	->addcolumn('checkbox','<input type="checkbox" name="offer_checkbox" class="offer_checkbox" value="{{$id}}"/>')
    	->rawcolumns(['checkbox','action'])
    	->make(true);
    }

    function postdata(Request $request){
        $validation = Validator::make($request->all(),
    	[
    		'Title' => 'required',
    		'Discount' => 'required',
    		'Begin_date' => 'required',
    		'Expire_date' => 'required'

    	]);

    	$error_array = array();
    	$success_output = '';

    	
        if ($validation->fails()){
    		foreach($validation->messages()->getMessages() as $field_name => $messages){
    			$error_array[] = $messages;
    		}
    	} else {
    		$catname = $request->get('Category');
    		$catid = DB::table('categories')->where('Name',$catname)->value('id');
            $discount = ($request->get('Discount') / 100);
            $products = DB::table('products')->where('cat_id',$catid)->get();
            foreach($products as $product){
                $price = $product->price;
                $percentage = $discount * $price;
                $newprice = $price - ($percentage);
                $offerprice = DB::table('products')->where('Title',$product->Title)->update(['price' => $newprice]);
                $offerpercentage = DB::table('products')->where('Title',$product->Title)->update(['percentage' => $percentage]);
            }
            
    		if ($request->get('button_action') == 'insert'){
	    		$offer = new Offer([
	    			'cat_id' => $catid,
	    			'Title' => $request->get('Title'),
	    			'Discount' => floatval($request->get('Discount')),
	    			'Begin_Date' => $request->get('Begin_date'),
	    			'Expire_Date' => $request->get('Expire_date'),
                    'Active' => 'yes'
	    		]);
	    		$offer->save();
	    		$success_output = '<div class="alert alert-success">Offer inserted succesfully</div>';
    		} if ($request->get('button_action') == 'update'){
    			$id = $request->get('offer_id');
    			$offer = Offer::find($id);
    			$offer->cat_id = $catid;
    			$offer->Title = $request->get('Title');
    			$offer->discount = floatval($request->get('Discount'));
    			$offer->Begin_Date = $request->get('Begin_date');
    			$offer->Expire_Date = $request->get('Expire_date');
    			$offer->save();
    			$success_output = '<div class="alert alert-success">Offer updated succesfully</div>';
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
    	$offer = Offer::find($id);
    	$catid = $offer->cat_id;
    	$category = DB::table('categories')->where('id',$catid)->value('Name');
    	$output = array(
    		'Category' => $category,
    		'Title' => $offer->Title,
    		'Discount' => $offer->discount,
    		'Begin_Date' => $offer->Begin_Date,
    		'Expire_Date' => $offer->Expire_Date
    	);

    	echo json_encode($output);
    }

    function removedata(Request $request){
    	$id = $request->input('id');
    	$offer = Offer::find($id);
        $catid = $offer->cat_id;
        $discount = $offer->discount;
        $products = DB::table('products')->where('cat_id',$catid)->get();
        foreach ($products as $product){
            $price = $product->price;
            $percentage = $product->Percentage;
            $newprice = $price + ($percentage);
            $offerprice = DB::table('products')->where('Title',$product->Title)->update(['price' => $newprice]);
            $offerprice = DB::table('products')->where('Title',$product->Title)->update(['Percentage' => 0.00]);
        }
    	if ($offer->delete()){
    		echo "Data deleted";
    	}
    }

    function massremove(Request $request){
    	$id = $request->input('id');
    	$offer = Offer::WhereIn('id',$id);
    	if ($offer->delete()){
    		echo "Data deleted";
    	}
    }    
}
