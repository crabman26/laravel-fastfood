@extends('main.master')
@section('content')
  <br />
  <div class="container box">
   <h3 align="center">Fastfood Registration</h3><br />
   <div class="form-group">
    <label>Enter Your Email</label>
    <input type="text" name="email" id="email" class="form-control input-lg" />
    <span id="error_email"></span>
   </div>
   <div class="form-group">
     <label>Enter your Name</label>
     <input type="text" name="name" id="name" class="form-control input-lg" />
   </div>
   <div class="form-group">
    <label>Enter Your Password</label>
    <input type="password" name="password" id="password" class="form-control input-lg" />
   </div>
   <div class="form-group" align="center">
    <button type="button" name="register" id="register" class="btn btn-info btn-lg">Register</button>
     <input type="reset" name="Reset" class="btn btn-warning" value="Reset" />
   </div>
   {{ csrf_field() }}
   
   <br />
   <br />
  </div>
<script>
$(document).ready(function(){
  $('#email').blur(function(){
    var error_email = '';
    var email = $('#email').val();
    var _token = $('input[name="_token"]').val();
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(!filter.test(email)){    
      $('#error_email').html('<label class="text-danger">Invalid Email</label>');
      $('#email').addClass('has-error');
      $('#register').attr('disabled', 'disabled');
    } else{
     $.ajax({
      url:"{{ route('email_available.check') }}",
      method:"POST",
      data:{email:email, _token:_token},
      success:function(result){
       if(result == 'unique'){
        $('#error_email').html('<label class="text-success">Email Available</label>');
        $('#email').removeClass('has-error');
        $('#register').attr('disabled', false);
       } else{
        $('#error_email').html('<label class="text-danger">E-mail already exists in database</label>');
        $('#email').addClass('has-error');
        $('#register').attr('disabled', 'disabled');
       }
      }
     })
  }
 });

 $('') 
});
</script>
@endsection

