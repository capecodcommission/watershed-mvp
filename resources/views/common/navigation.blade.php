<nav>
    <ul>
        <li class="left"><img src="https://www.watershedmvp.org/images/mvplogo.png" alt="WatershedMVP 4.0 by Cape Cod Commission"></li>
        <li class="right"><a href="{{url('/help')}}" class="button"><i class="fa fa-btn fa-question-circle"></i> Help</a></li>
        @if (Auth::guest())
            <li class="right"><a href="{{ url('/register') }}" class="button"><i class="fa fa-btn fa-user-plus"></i> Register</a></li>
            <li class="right"><a href="{{ url('/login') }}" class="button"><i class="fa fa-btn fa-sign-in"></i> Login</a></li>
        @else
            <li class="right"><a class="button" href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
            <li class="right"><a class="button" href="{{ url('/start') }}"><i class="fa fa-plus"></i> Start a new Scenario</a></li>
            <li class="right"><a class="button" href="{{ url('/') }}"><i class="fa fa-home"></i> Dashboard</a></li>
        @endif
    </ul>
</nav>