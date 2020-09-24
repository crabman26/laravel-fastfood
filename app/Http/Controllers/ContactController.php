<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use DB;
use Validator;
use Mail;

class ContactController extends Controller
{
    //

    function viewcontacts(){
    	$contacts = Contact::all()->toArray();

    	return view('contactform.index',compact('contacts'));
    }

    function savecontact(Request $request){
    	$this->validate($request,[
            'Name' => 'required|max:30',
    		'Surname' => 'required|max:30',
    		'E-mail' => 'required|email|max:40',
    		'Phone' => 'required|numeric',
    		'Message' => 'required',
        ]);

        $contact = new Contact([
    		'Name' => $request->get('Name'),
    		'Surname' => $request->get('Surname'),
    		'E-mail' => $request->get('E-mail'),
    		'Phone' => $request->get('Phone'),
    		'Message' => $request->get('Message'),
    	]);

        $contact->save();
        $success_output = 'Contact form submitted succesfully. We will get in touch with you shortly.';
        return redirect()->route('contact')->with('success',$success_output);
    }

    function replyform(Request $request){
    	$mail = DB::Table('contacts')->select('E-mail as Mail')->where('id',$request->Id)->get();
    	return view('contactform.reply',compact('mail'));
    }

    function replymail(Request $request){
    	$email = $request->get('email');
    	$this->validate($request,[
    		'Reply' => 'required',
    	]);

    	Mail::send('category.index',
    	array('Message' => $request->get('Reply')
    	), 
    	function($message){
    		$message->from('info@tipota.com');
    		$message->to('saquib.rizwan@cloudways.com')->subject('Reply to contact from fastfood');
    	});

    	return redirect()->route('contactform')->with('success','E-mail sent succesfully.');
    }
}
