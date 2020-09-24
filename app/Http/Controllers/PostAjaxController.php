<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Post;
use Validator;
use DataTables;

class PostAjaxController extends Controller
{
    //
    function index(){
    	return view('post.index');
    }

    function getdata(){
    	$posts = DB::table('posts')
    	->select('id','title','Text');
    	return DataTables::of($posts)
    	->addcolumn('action', function($post){
    		return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$post->id.'"><i class="glyphicon glyphicon-edit"></i>Edit</a>
    		        <a href="#" class="btn btn-xs btn-danger delete" id="'.$post->id.'"><i class="glyphicon glyphicon-remove"></i>Delete</a>';
    	})
    	->addcolumn('checkbox','<input type="checkbox" name="post_checkbox" class="post_checkbox" value="{{$id}}"/>')
    	->rawcolumns(['checkbox','action'])
    	->make(true);
    }

    function postdata(Request $request){
    	$validation = Validator::make($request->all(),[
    		'Title' => 'required',
    		'Text' => 'required'
    	]);
    	$error_array = array();
    	$success_output = '';
    	if ($validation->fails()){
    		foreach($validation->messages()->getMessages() as $field_name => $messages){
    			$error_array[] = $messages;
    		}
    	} else {
    		if ($request->get('button_action') == 'insert'){
    			$post = new Post([
    				'Title' => $request->get('Title'),
    				'Text' => $request->get('Text')
    			]);
    			$post->save();
    			$success_output = '<div class="alert alert-success">Post created succesfully.</div>';
    		} if ($request->get('button_action') == 'update'){
    			$post = Post::find($request->get('post_id'));
    			$post->Title = $request->get('Title');
    			$post->text = $request->get('text');
    			$post->save();
    			$success_output = "<div class='alert alert-success'>Post updated succesfully</div>";

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
    	$post = Post::find($id);
    	$output = array(
    		'Title' => $post->title,
    		'Text' => $post->Text
    	);

    	echo json_encode($output);
    }

    function removedata(Request $request){
    	$id = $request->input('id');
    	$post = Post::find($id);
    	if ($post->delete()){
    		echo "Post deleted";
    	}
    }

    function massremove(Request $request){
    	$post = Post::WhereIn('id',$request->input('id'));
    	if ($post->delete()){
    		echo "Data deleted";
    	}
    }

    function postlist(){
        $posts = DB::table('posts')
            ->orderByRaw('title')
            ->get();

        return view('main.blogs',compact('posts'));
    }

    function posttitle(Request $request){
        $post = DB::table('posts')
            ->where('title',$request->title)
            ->first();

        return view('main.blogtitle',compact('post'));
    }
}
