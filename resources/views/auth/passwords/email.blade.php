@extends('layouts.app')
@section('pagetitle') Reset Password @endsection

@section('content')
<div class="wrapper">
    <div class="centered">
        <div class="login-register">
            <h2 class="section-title">Forgot Password</h2>
                <p>Please enter the email address you used when originally registering on the site. You can also <a href="{{ url('/register') }}">Register</a> if you don't have an account, or try <a href="{{ url('/login') }}">logging in again</a>.</p>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {!! csrf_field() !!}

                        <p>
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email')) <small class="error">{{ $errors->first('email') }}</small> @endif 
                        </p>
                        
                        <p>
                            <button type="submit" class="button--cta">
                                    <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
                                </button>
                        </p>

                    </form>
        </div>  
    </div>
</div>

    

@endsection