@extends('master')
@section('content')
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Name</th>
			<th>Surname</th>
			<th>E-mail</th>
			<th>Phone</th>
			<th>Message</th>
			<th>Actions</th>
		</tr>
	</thead>
	@foreach($contacts as $contact)
		<tr>
			<td>{{ $contact['Name'] }}</td>
			<td>{{ $contact['Surname'] }}</td>
			<td>{{ $contact['E-mail'] }}</td>
			<td>{{ $contact['Phone'] }}</td>
			<td>{{ $contact['Message'] }}</td>
			<td><a href="{{ url('contact/replyform',['Id' => $contact['id']]) }}">Reply</a></td>
		</tr>
	@endforeach
</table>
@endsection