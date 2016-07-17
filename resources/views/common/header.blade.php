		<link rel="stylesheet" href="{{url('/css/app.css')}}">
  	<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>  
  	<script>window.name = 'wmvp_results_{{$scenario->ScenarioID}}';</script>
	</head>
	<body>
		<div class="wrapper">
		<div class="content full-width">
		   <p><img src="http://www.watershedmvp.org/Images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission"></p>
			<nav>
					@if (Auth::guest())
                        <a href="{{ url('/login') }}" class="button"><i class="fa fa-btn fa-sign-in"></i> Login</a>
                        <a href="{{ url('/register') }}"><i class="fa fa-btn fa-user-plus"></i> Register</a>
                    @else
                    	<a class="button" href="{{ url('/home') }}"><i class="fa fa-home"></i> Home</a>
                    	<a class="button" href="{{ url('/') }}"><i class="fa fa-plus"></i> Start a new Scenario</a>
                        <a class="button" href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a>

                    @endif


				</ul>
			</nav>