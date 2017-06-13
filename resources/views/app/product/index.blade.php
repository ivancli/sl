@extends('layouts.default')

@section('title', 'Products - SpotLite')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/app.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.5.0/introjs.css">
@stop

@section('body')
    <div id="sl">
        <default-template v-cloak>
            <default-header slot="default-header"></default-header>
            <default-content slot="default-content">
                <content-header slot="content-header">
                    <span slot="content-header-title">Products</span>
                </content-header>
                <content-body slot="content-body"></content-body>
            </default-content>
        </default-template>
    </div>
@stop

@section('scripts')
    <script src="{{mix('/js/app/product/index.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.5.0/intro.js"></script>
    <script src="{{asset('/js/plugins/products_intro.js')}}"></script>
@stop
