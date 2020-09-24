@extends('main.master')
@section('content')
<h2 class="header">News/Offers</h2>
<ul class="list-group">
	@foreach($posts as $post)
		<li class="list-group-item"><a href="{{ route('posttitle',['title' => $post->title]) }}">{{ $post->title}}</a></li>
	@endforeach
</ul>
@endsection