@extends('layouts.start')

@section('content')
<div id="login-backdrop"></div>
<div class="wrapper centered" id="login">
    <div class="centered">
        <div class="login-register">
            <button class="close-button" id ="closeButton">
                <i class="fa fa-times"></i>
            </button>
            <!-- <img class = 'login-image' src = 'https://user-images.githubusercontent.com/16725828/70354678-3e457600-183e-11ea-8a95-6baf544ec121.jpg'/> -->
            <img width="300" style="align-self: center;" src="{{substr($_ENV['CCC_ICONS_PNG'],0, -4)}}wmvp_large.jpg" alt="WatershedMVP 4.1 by Cape Cod Commission">
            <h2 class="section-title">Welcome to the new and improved WatershedMVP 4.1!</h2>
            <p>The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem.</p>

            <p>The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). To learn more about the WatershedMVP application and the Cape Cod Commission and its SIO, please <a href="http://www.capecodcommission.org/index.php?id=205" target="_blank">contact us</a>.</p>

            <p>This new version of the application allows users to save and re-edit their scenarios. To facilitate this, we needed to create individual user accounts.</p>


            <p style = 'padding-bottom: 10px'>Please enter your email address and password, or <a href="{{ url('/register') }}">Register</a> if you don't have an account.</p>  

            @include('common.errors')
            
            <form class="form-horizontal" role="form" method="POST" action="{{ secure_url('/login') }}">
                {!! csrf_field() !!}
                <p>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                    @if ($errors->has('email')) <small class="error">{{ $errors->first('email') }}</small> @endif 
                </p>
                <p>
                    <label>Password</label>
                    <input type="password" name="password" id="password">
                    @if ($errors->has('password')) <small class="error">{{ $errors->first('password') }}</small> @endif 
                </p>
                <p>
                    <input type="checkbox" name="remember"> Remember Me
                </p>
                <p>
                    <button type="submit" class="button--cta round"><i class="fa fa-check"></i> Login</button>
                    <span>&nbsp;<a href="{{ secure_url('/password/reset') }}">Forgot Password?</a></span>
                    <span class="right">
                        <a href="{{ url('/register') }}" class="button--cta"><i class="fa fa-btn fa-user-plus"></i> Register</a>
                        <a href="{{url('/help')}}" class="button--cta"><i class="fa fa-btn fa-question-circle"></i> Help</a>
                    </span>
                </p>
            </form>
            <p style = 'padding-top: 10px'>If you are looking for the old watershedMVP, it can still be found <a href="http://2014.watershedmvp.org" target="_blank">here.</a></p>
        </div>  
    </div>
</div>
<script>
    $(document).ready(function() {
        // Add scrolling to main body on login only
        $('#app-layout').addClass('scrollable');
        $('#login-backdrop').show();

        $('#closeButton').click(function()  {
            $('#login-backdrop').hide();
            $('#login').hide();
        });
    })
</script>
@endsection
