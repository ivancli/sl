@extends('layouts.default')

@section('title', 'Item Metas - SpotLite')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/app.css')}}">
@stop

@section('body')
    <div id="sl">
        <default-template v-cloak>
            <default-header slot="default-header"></default-header>
            <default-sidebar slot="default-sidebar"></default-sidebar>
            <default-content slot="default-content">
                <content-header slot="content-header">
                    <span slot="content-header-title">
                        Item Metas for
                        @if(!is_null($item->name))
                            {{$item->name}}
                        @else
                            Unnamed Item
                        @endif
                    </span>
                </content-header>
                <content-body slot="content-body"></content-body>
            </default-content>
        </default-template>
    </div>
@stop

@section('scripts')
    <script>
        var editingItem = JSON.parse('{!! addslashes($item->toJson()) !!}');
    </script>
    <script src="{{mix('/js/app/item_meta/index.js')}}"></script>
@stop