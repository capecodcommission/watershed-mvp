<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>  
    <title>WatershedMVP 3.0</title>
    <link rel="stylesheet" href="{{url('css/app.css')}}">



</head>
<body id="app-layout">
<div class="wrapper">
        <div class="content full-width">
 <p><img src="http://www.watershedmvp.org/Images/mvplogo.png" alt="WatershedMVP 3.0 by Cape Cod Commission"></p>
            <nav>
                    @if (Auth::guest())
                        <a href="{{ url('/login') }}" class="button"><i class="fa fa-btn fa-sign-in"></i> Login</a>
                        <a href="{{ url('/register') }}"><i class="fa fa-btn fa-user-plus"></i> Register</a>
                    @else
                        <a class="button" href="{{ url('/') }}"><i class="fa fa-home"></i> Dashboard</a>
                        <a class="button" href="{{ url('/start') }}"><i class="fa fa-plus"></i> Start a new Scenario</a>
                        <a class="button" href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a>

                    @endif


                </ul>
            </nav>

    @yield('content')
</div>
</div>
  

</body>
</html>
