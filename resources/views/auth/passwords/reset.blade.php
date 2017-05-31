@extends('layouts.auth')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/auth.css')}}">
@stop

@section('body')
    <div id="sl">
        <auth>
            <reset slot="box-body"></reset>
        </auth>
    </div>
@stop

@section('scripts')
    <script>
        var token = "{{$token}}";
    </script>
    <script src="{{mix('/js/auth/reset.js')}}"></script>
@stop