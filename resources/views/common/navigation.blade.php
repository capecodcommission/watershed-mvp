<nav>
    <ul>
        <li class="left"><img src="{{$_ENV['CCC_ICONS_PNG']}}mvplogo.png" alt="WatershedMVP 4.1 by Cape Cod Commission"></li>
        <li class="right"><a href="{{url('/help')}}" class="button"><i class="fa fa-btn fa-question-circle"></i> Help</a></li>
        @if (Auth::guest())
            <li class="right"><a href="{{ url('/register') }}" class="button"><i class="fa fa-btn fa-user-plus"></i> Register</a></li>
            <li class="right"><a href="#" class="button" id="loginButton"><i class="fa fa-btn fa-sign-in"></i> Login</a></li>
        @else
            <li class="right"><a class="button" href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
            <li class="right"><a class="button" href="{{ url('/start') }}"><i class="fa fa-plus"></i> Start a new Scenario</a></li>
            <li class="right"><a class="button" href="{{ url('/') }}"><i class="fa fa-home"></i> Dashboard</a></li>
        @endif
    </ul>
</nav>
<script>
    $(document).ready(function() {
        $('#loginButton').click(function()  {
            // If current URL is /login, open login panel
            if (window.location.pathname === '/login') {
                $('#login-backdrop').show();
                $('#login').show();
            }

            // Else, navigate to /login
            else {
                window.location.href = '/login';
            }
        });
    })
</script>