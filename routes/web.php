<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Main controllers
Route::get('/index', 'MainController@index');
Route::get('/categories', 'CategoryAjaxController@categorieslist');
Route::get('/products/{catid}', 'ProductAjaxController@categoryproducts')->name('categoryproducts');
Route::get('/about', 'MainController@about');
Route::get('/blogs', 'PostAjaxController@postlist');
Route::get('/faq', 'MainController@faq');
Route::get('/blog/{title}', 'PostAjaxController@posttitle')->name('posttitle');
Route::get('/contact', 'MainController@contact')->name('contact');

//Contact controllers
Route::post('/contact/savecontact','ContactController@savecontact');
Route::get('/contactform','ContactController@viewcontacts');
Route::get('/contact/replyform/{Id}','ContactController@replyform');
Route::post('/contact/replymail/{Mail}','ContactController@replymail');

//Login controllers
Route::get('/main', 'MainController@login');
Route::post('/main/checklogin', 'MainController@checklogin');
Route::get('main/successlogin', 'MainController@successlogin');
Route::get('main/logout', 'MainController@logout');

Route::get('/member','MainController@member')->name('memberorders');
Route::get('/member/profile','MainController@memberprofile')->name('memberprofile');

//Register controllers
Route::get('/email_available', 'EmailAvailable@index');
Route::post('/email_available/check', 'EmailAvailable@check')->name('email_available.check');

//Category controllers
Route::get('categorydata', 'CategoryAjaxController@index')->name('categorydata');
Route::get('category/getdata', 'CategoryAjaxController@getdata')->name('category.getdata');
Route::post('category/postdata', 'CategoryAjaxController@postdata')->name('category.postdata');
Route::get('categorydata/fetchdata', 'CategoryAjaxController@fetchdata')->name('categorydata.fetchdata');
Route::get('categorydata/removedata', 'CategoryAjaxController@removedata')->name('categorydata.removedata');
Route::get('categorydata/massremove', 'CategoryAjaxController@massremove')->name('categorydata.massremove');
Route::get('categorydata/getcategories', 'CategoryAjaxController@getcategories')->name('categorydata.getcategories');

//Product controllers
Route::get('productdata','ProductAjaxController@index')->name('productdata');
Route::get('product/getdata','ProductAjaxController@getdata')->name('product.getdata');
Route::post('product/postdata','ProductAjaxController@postdata')->name('product.postdata');
Route::get('product/fetchdata','ProductAjaxController@fetchdata')->name('product.fetchdata');
Route::get('product/removedata','ProductAjaxController@removedata')->name('product.removedata');
Route::get('product/massremove','ProductAjaxController@massremove')->name('product.massremove');
Route::get('product/getproducts','ProductAjaxController@getproducts')->name('product.getproducts');

//Post controllers
Route::get('postdata','PostAjaxController@index')->name('postdata');
Route::get('post/getdata','PostAjaxController@getdata')->name('post.getdata');
Route::post('post/postdata','PostAjaxController@postdata')->name('post.postdata');
Route::get('post/fetchdata','PostAjaxController@fetchdata')->name('post.fetchdata');
Route::get('post/removedata', 'PostAjaxController@removedata')->name('post.removedata');
Route::get('post/massremove','PostAjaxController@massremove')->name('post.massremove');

//Offer controllers
Route::get('offerdata','OfferAjaxController@index')->name('offerdata');
Route::get('offer/getdata','OfferAjaxController@getdata')->name('offer.getdata');
Route::post('offer/postdata','OfferAjaxController@postdata')->name('offer.postdata');
Route::get('offer/fetchdata','OfferAjaxController@fetchdata')->name('offer.fetchdata');
Route::get('offer/removedata','OfferAjaxController@removedata')->name('offer.removedata');
Route::get('offer/massremove','OfferAjaxController@massremove')->name('offer.massremove');

//Order controllers
Route::get('orderdata','OrderAjaxController@index')->name('orderdata');
Route::get('order/getdata','OrderAjaxController@getdata')->name('order.getdata');
Route::post('order/postdata','OrderAjaxController@postdata')->name('order.postdata');
Route::post('order/orderuser','OrderAjaxController@orderuser')->name('order.orderuser');
Route::post('order/userupdate','OrderAjaxController@userupdate')->name('order.userupdate');
Route::get('order/fetchdata','OrderAjaxController@fetchdata')->name('order.fetchdata');
Route::get('order/removedata','OrderAjaxController@removedata')->name('order.removedata');
Route::get('order/massremove','OrderAjaxController@massremove')->name('order.massremove');
Route::get('order/getusers','OrderAjaxController@getusers')->name('order.getusers');
Route::get('order/memberorder','OrderAjaxController@memberorder')->name('order.memberorder');
Route::get('order/cancelorder','OrderAjaxController@cancelorder')->name('order.cancelorder');

//User controllers
Route::get('usersajax','UserAjaxController@index')->name('usersajax');
Route::get('usersajax/getdata','UserAjaxController@getdata')->name('usersajax.getdata');
Route::post('usersajax/postdata','UserAjaxController@postdata')->name('usersajax.postdata');
Route::get('usersajax/fetchdata','UserAjaxController@fetchdata')->name('usersajax.fetchdata');
Route::get('usersajax/removedata','UserAjaxController@removedata')->name('usersajax.removedata');
Route::get('usersajax/massremove','UserAjaxController@massremove')->name('usersajax.massremove');
Route::get('usersajax/memberdata','UserAjaxController@memberdata')->name('usersajax.memberdata');
Route::get('usersajax/userid','UserAjaxController@userid')->name('userajax.userid');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

