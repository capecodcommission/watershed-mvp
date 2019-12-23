<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>  
    <title>WatershedMVP 4.0</title>
    <?php if( env('APP_ENV') == 'production' ) : ?>
		<link rel="stylesheet" href="{{secure_url('/css/app.css')}}">
	<?php else :?>
		<link rel="stylesheet" href="{{url('/css/app.css')}}">
	<?php endif; ?>
    <!-- <link rel="stylesheet" href="{{secure_url('css/app.css')}}"> -->
</head>
<body id="app-layout">
<div class="wrapper">
    <div class="content full-width">
        @include('common.navigation')

        @yield('content')
    </div>
</div>
  

</body>
</html>
