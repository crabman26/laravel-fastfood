<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;

class MainController extends Controller
{
    //
    function index()
    {
     return view('main.index');
    }

    function categories()
    {
     return view('main.categories');
    }

    function about()
    {
     return view('main.about');
    }

    function blog()
    {
     return view('main.blogs');
    }

    function faq()
    {
     return view('main.faq');
    }

    function contact()
    {
     return view('main.contact');
    }

    function login()
    {
     return view('main.login');
    }

    function member(){
        return view('member.index');
    }

    function memberprofile(){
        return view('member.profile');
    }

    function checklogin(Request $request)
    {
     $this->validate($request, [
      'email'   => 'required|email',
      'password'  => 'required|alphaNum|min:3'
     ]);

     $user_data = array(
      'email'  => $request->get('email'),
      'password' => $request->get('password')
     );

     if(Auth::attempt($user_data))
     {
      $role = Auth::user()->Role; 
       switch ($role) {
            case 'Administrator':
                return redirect('categorydata');
                break;
            case 'Member':
                return redirect('member');
                break; 
        }
     }
     else
     {
      return back()->with('error', 'Wrong Login Details');
     }

    }

    function successlogin()
    {
     return view('main.successlogin');
    }

    function logout()
    {
     Auth::logout();
     return redirect('main');
    }
}
