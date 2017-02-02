@extends('layouts.auth')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/auth.css')}}">
@stop

@section('body')
    <div id="sl">
        <auth>
            <pricing-table slot="pre-box-body"></pricing-table>
            <register slot="login-box-body"></register>
        </auth>
    </div>
@stop

@section('scripts')
    <script src="{{mix('/js/register.js')}}"></script>
@stop