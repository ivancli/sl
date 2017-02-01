@extends('layouts.auth')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/auth.css')}}">
@stop

@section('body')
    <div id="sl">
        <auth>
            <register></register>
        </auth>
    </div>
@stop

@section('scripts')
    <script src="{{mix('/js/register.js')}}"></script>
@stop