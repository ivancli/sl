@extends('layouts.auth')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/auth.css')}}">
@stop

@section('body')
    <div id="sl">
        <auth>
            <login slot="box-body"></login>
        </auth>
    </div>
@stop

@section('scripts')
    <script src="{{mix('/js/auth/login.js')}}"></script>
@stop