<!DOCTYPE html>
<html>
	<head>
		<title>Datatables Server Side Processing in Laravel</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>       
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo asset('css/style.css')?>" type="text/css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="col-md-12 col-sm-12 col-xs-12" id="outter">
				<header>
					<a href="index"><img src="<?php echo asset('storage/fastfood_logo.jpg')?>" alt="logo"/></a>
					<nav id="main-nav">
						<ul>
							@php
							  $role = Auth::user()->Role;
							@endphp
							@if($role == 'Administrator')
								<li class="main-link"><a href="categorydata">Categories</a></li>
								<li class="main-link"><a href="productdata">Products</a></li>
								<li class="main-link"><a href="offerdata">Offers</a></li>
								<li class="main-link"><a href="orderdata">Orders</a></li>
								<li class="main-link"><a href="postdata">Posts</a></li>
								<li class="main-link"><a href="contactform">Contact</a></li>
								<li class="main-link"><a href="usersajax">Users</a></li>
							@elseif($role == 'Member')
		                        <li class="main-link"><a href="{{route('memberorders')}}">Orders</a></li>
		                        <li class="main-link"><a href="{{route('memberprofile')}}">Profile</a></li>
                      		@endif
							@if(isset(Auth::user()->email))
                         		<li id="admin-link" data-email="{{ Auth::user()->email }}"><a href="{{ url('/main/logout') }}">Logout</a></li>
                     		@endif
						</ul>
					</nav>
				</header>
				<br />
				<main>
					@yield('content')
				</main>
			</div>
</body>
</html>