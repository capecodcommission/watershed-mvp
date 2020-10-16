@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="centered">
        <div class="login-register">
            <h2 class="section-title">Create a Registration Account</h2>
           <p>The Cape Cod Commission developed the WatershedMVP application for professionals, municipal officials and community members in order to assist in creating the most cost-effective and efficient solutions to Cape Cod’s wastewater problem.</p>

            <p>The application is an informational resource intended to provide regional estimates for planning purposes. WatershedMVP is an initiative of the Cape Cod Commission’s Strategic Information Office (SIO). To learn more about the WatershedMVP application and the Cape Cod Commission and its SIO, please <a href="http://www.capecodcommission.org/index.php?id=205" target="_blank">contact us</a>.</p>

            @include ('common.errors')

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                {!! csrf_field() !!}
                <p>
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name') }}">
                    @if ($errors->has('name')) <small class="error">{{ $errors->first('name') }}</small> @endif 
                </p>


                <p>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                    @if ($errors->has('email')) <small class="error">{{ $errors->first('email') }}</small> @endif 
                </p>

                
                <p>
                    <label>Password</label>
                    <input type="password" name="password">
                    @if ($errors->has('password')) <small class="error">{{ $errors->first('password') }}</small> @endif 
                </p>    

                
                <p>
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation">
                    @if ($errors->has('password_confirmation')) <small class="error">{{ $errors->first('password_confirmation') }}</small> @endif 
                </p>

                
                <p>
                        <button type="submit" class="button--cta">
                            <i class="fa fa-btn fa-user"></i> Register
                        </button>
                </p>

            </form>
        </div>
    </div>
</div>
@endsection
