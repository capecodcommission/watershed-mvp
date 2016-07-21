@extends('layouts.app')

@section('content')
        <div class="container">
            <div class="content">
                <div class="title">We're sorry, Scenario {{$scenarioid}} could not be found. Please view your <a href="{{url('/')}}">dashboard</a> and try again.</div>
            </div>
        </div>
@endsection