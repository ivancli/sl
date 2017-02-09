@extends('layouts.auth')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/auth.css')}}">
@stop

@section('body')
    <div id="sl">
        <auth>
            <forgot slot="box-body"></forgot>
        </auth>
    </div>
@stop

@section('scripts')
    <script src="{{mix('/js/auth/forgot.js')}}"></script>
@stop