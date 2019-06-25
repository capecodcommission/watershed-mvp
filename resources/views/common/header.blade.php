	<?php if( env('APP_ENV') == 'production' ) : ?>
		<link rel="stylesheet" href="{{secure_url('/css/app.css')}}">
	<?php else :?>
		<link rel="stylesheet" href="{{url('/css/app.css')}}">
	<?php endif; ?>
	<!-- <link rel="stylesheet" href="{{secure_url('/css/app.css')}}"> -->
  	<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>  
  	<script>window.name = 'wmvp_results_{{$scenario->ScenarioID}}';</script>
	</head>
	<body>
		<div class="wrapper">
		<div class="content full-width">
		    @include('common.navigation')