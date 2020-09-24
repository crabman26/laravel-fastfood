@extends('main.master')
@section('content')
<h2 class="header">{{$post->title}}</h2>
<p>{{$post->Text}}</p>
@endsection