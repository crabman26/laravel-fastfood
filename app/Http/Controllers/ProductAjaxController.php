<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use DataTables;
use Validator;
use DB;

class ProductAjaxController extends Controller
{
    //
    function index(){
    	return view('product.index');
    }

    function getdata(Request $request){
        if ($request->ajax()){
            if ($request->category){
                if($request->available){
                    $products = DB::table('products')
                    ->join('categories','categories.id','=','products.cat_id')
                    ->select('products.id','categories.Name','Title','price','description')
                    ->where('categories.Name',$request->category)
                    ->where('products.available',$request->available);
                } else {
                    $products = DB::table('products')
                    ->join('categories','categories.id','=','products.cat_id')
                    ->select('products.id','categories.Name','Title','price','description')
                    ->where('categories.Name',$request->category);
                }
            } else if ($request->available){
                $products = DB::table('products')
                ->join('categories','categories.id','=','products.cat_id')
                ->select('products.id','categories.Name','Title','price','description')
                ->where('products.available',$request->available);
            } else {
    	       $products = DB::table('products')
                ->join('categories','categories.id','=','products.cat_id')
                ->select('products.id','categories.Name','Title','price','description');
            }
        	return Datatables::of($products)
            ->addcolumn('action',function($product){
                return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$product->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                        <a href="#" class="btn btn-xs btn-danger delete" id="'.$product->id.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
            })
            ->addcolumn('checkbox','<input type="checkbox" name="product_checkbox" class="product_checkbox" value="{{$id}}"/>')
            ->rawcolumns(['checkbox','action'])
        	->make(true);
        }
    }

     function postdata(Request $request){
    	$validation = Validator::make($request->all(),[
        	'Title' => 'required',
        	'Price' => 'required',
        	'Description' => 'required',
        	'Available' => 'required'
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
    		if ($request->get('button_action') == 'insert'){
	    		$product = new Product([
	    			'cat_id' => $catid,
	    			'Title' => $request->get('Title'),
	    			'Price' => $request->get('Price'),
	    			'Description' => $request->get('Description'),
	    			'Available' => $request->get('Available')
	    		]);
	    		$product->save();
	    		$success_output = '<div class="alert alert-success">Product Inserted</div>';

    		} if ($request->get('button_action') == 'update'){
                $product = Product::find($request->get('product_id'));
                $product->cat_id = $catid;
                $product->Title = $request->get('Title');
                $product->Price = $request->get('Price');
                $product->Description = $request->get('Description');
                $product->Available = $request->get('Available');
                $product->save();
                $success_output = '<div class="alert alert-success">Product Updated</div>';
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
        $product = Product::find($id);
        $catid = $product->cat_id;
        $category = DB::table('categories')->where('id',$catid)->value('Name');
        $output = array(
            'Category' => $category,
            'Title' => $product->Title,
            'Price' => $product->price,
            'Description' => $product->description,
            'Available' => $product->available
        );

        echo json_encode($output);
    }

     function removedata(Request $request){
        $product = Product::find($request->input('id'));
        if ($product->delete()){
            echo "Data deleted";
        }
    }

     function massremove(Request $request){
        $product = Product::WhereIn('id',$request->input('id'));
        if ($product->delete()){
            echo "Data deleted";
        }
    }

     function getproducts(){
        $products = DB::table('products')
                    ->groupBy('Title')
                    ->get();

        echo json_encode($products);
    }

    function categoryproducts(Request $request){
        $products = DB::table('products')
                    ->orderByRaw('Title')
                    ->where('cat_id',$request->catid)
                    ->get();

        return view('main.categoryproducts',compact('products'));
    }
}
