@extends('layouts.auth')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/auth.css')}}">
@stop

@section('body')
    <div id="sl">
        <auth>
            <login></login>
        </auth>
    </div>
@stop

@section('scripts')
    <script src="{{mix('/js/login.js')}}"></script>
@stop