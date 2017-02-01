@extends('layouts.auth')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/auth.css')}}">
@stop

@section('body')
    <div id="sl">
        <auth>
            <forgot></forgot>
        </auth>
    </div>
@stop

@section('scripts')
    <script src="{{mix('/js/forgot.js')}}"></script>
@stop