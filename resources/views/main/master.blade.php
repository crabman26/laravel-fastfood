<!DOCTYPE html>
<html>
 <head>
  <title>Laravel Fastfood</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo asset('css/style.css')?>" type="text/css"> 
 </head>
 <body>
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-sm-10 col-xs-12" id="outter">
        <header>
          <a href="index"><img src="<?php echo asset('storage/fastfood_logo.jpg')?>" alt="logo"/></a>
          <nav id="main-nav">
            <ul>
                <li class="main-link"><a href="../index">Index</a></li>
                <li class="main-link"><a href="../about">About us</a></li>
                <li class="main-link"><a href="../blogs">Blog</a></li>
                <li class="main-link"><a href="../faq">Faq</a></li>
                <li class="main-link"><a href="../categories">Categories</a></li>
                <li class="main-link"><a href="../contact">Contact</a></li>
                <li id="login-link"><a href="main">Login |</a></li>
                <li><a href="register">Register</a></li>
            </ul>
          </nav>
        </header>
        <main>
          @yield('content')
        </main>
      </div>
    </div>
  </div>
</body>

</html>