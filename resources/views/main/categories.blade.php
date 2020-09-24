@extends('main.master')
@section('content')
  <h2 align="center">Categories catalogue</h2>
    <table class="table table-bordered">
      @foreach($categories as $category)
        <tr>
          <td><a href="{{route('categoryproducts',['catid' => $category->id])}}">{{$category->Name}}</a></td>
        </tr>
      @endforeach
</table>
@endsection
        