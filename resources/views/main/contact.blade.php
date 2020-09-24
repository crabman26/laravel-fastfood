@extends('main.master')
@section('content')
@if(count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{$error}}</li>
        @endforeach
      </ul>
    </div>
   @endif
   @if(\Session::has('success'))
  <div class="alert alert-success">
   <p>{{ \Session::get('success') }}</p>
  </div>
  @endif
  <form method="post" action=" {{ url('contact/savecontact') }}">
	{{ csrf_field() }}
	<div class="form-group">
		<label for="Name">Name:</label>
		<input type="text" name="Name" id="Name" class="form-control"/>
	</div>
	<div class="form-group">
		<label for="Surname">Surname:</label>
		<input type="text" name="Surname" id="Surname" class="form-control"/>
	</div>
	<div class="form-group">
		<label for="E-mail">E-mail:</label>
		<input type="e-mail" name="E-mail" id="E-mail" class="form-control"/>
	</div>
	<div class="form-group">
		<label for="Phone">Phone:</label>
		<input type="text" name="Phone" id="Phone" class="form-control">
	</div>
	<div class="form-group">
		<label for="Message">Message:</label>
		<textarea name="Message" id="Message" cols="30" class="form-control"></textarea>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary">Submit</button>
		<button type="reset" class="btn btn-warning">Reset</button>
	</div>
</form>
@endsection