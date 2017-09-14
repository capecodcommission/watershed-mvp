@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="centered">
        <div class="login-register">
            <h2 class="section-title">Welcome to the new and improved WatershedMVP 3.1!</h2>
            <p>The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem.</p>

            <p>The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). To learn more about the WatershedMVP application and the Cape Cod Commission and its SIO, please <a href="http://www.capecodcommission.org/index.php?id=205" target="_blank">contact us</a>.</p>

            <p>This new version of the application allows users to save and re-edit their scenarios. To facilitate this, we needed to create individual user accounts.</p>


            <p>Please enter your email address and password, or <a href="{{ url('/register') }}">Register</a> if you don't have an account.</p>  

            @include('common.errors')
            
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
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
                    <button type="submit" class="button round"><i class="fa fa-check"></i> Login</button> <span>&nbsp;<a href="{{ url('/password/reset') }}">Forgot Password?</a></span>
                </p>
            </form><br><br>
            <p>If you are looking for the old watershedMVP, it can still be found <a href="http://2014.watershedmvp.org" target="_blank">here.</a></p>
        </div>  
    </div>
</div>
@endsection
