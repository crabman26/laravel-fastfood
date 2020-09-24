<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use DataTables;
use Validator;
use DB;

class CategoryAjaxController extends Controller
{
    //
    function index(){
     return view('category.index');
     //http://127.0.0:8000/ajaxdata
    }

    function getdata()
    {
     $categories = Category::select('id','Name');
     return Datatables::of($categories)
     ->addColumn('action',function($category){
     	return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$category->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</a>
     	<a href="#" class="btn btn-xs btn-danger delete" id="'.$category->id.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
     })
     ->addColumn('checkbox', '<input type="checkbox" name="category_checkbox[]" class="category_checkbox" value="{{$id}}" />')
            ->rawColumns(['checkbox','action'])
     ->make(true);
    }

    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'Name' => 'required',
        ]);

        $error_array = array();
        $success_output = '';
        if ($validation->fails())
        {
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
        }
        else
        {
            if($request->get('button_action') == "insert")
            {
                $category = new Category([
                    'Name'    =>  $request->get('Name'),
                ]);
                $category->save();
                $success_output = '<div class="alert alert-success">Category Inserted</div>';
            } if($request->get('button_action') == 'update'){
                $category = Category::find($request->get('category_id'));
                $category->Name = $request->get('Name');
                $category->save();
                $success_output = '<div class="alert alert-success">Category Updated</div>';
            }
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }

    function fetchdata(Request $request)
    {
        $id = $request->input('id');
        $category = Category::find($id);
        $output = array(
            'Name'    =>  $category->Name
        );
        echo json_encode($output);
    }

    function removedata(Request $request)
    {
        $category = Category::find($request->input('id'));
        if($category->delete())
        {
            echo 'Data Deleted';
        }
    }

    function massremove(Request $request)
    {
        $category_id_array = $request->input('id');
        $category = Category::whereIn('id', $category_id_array);
        if($category->delete())
        {
            echo 'Data Deleted';
        }
    }

    function getcategories(){
    	$categories = DB::table('categories')
    				->groupby('Name')
    				->get();

    	echo json_encode($categories);
    }

    function categorieslist(){
        $categories = DB::table('categories')
            ->orderByRaw('Name ASC')
            ->get();

        return view('main.categories',compact('categories'));
    }

}
