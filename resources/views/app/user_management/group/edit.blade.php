@extends('layouts.default')

@section('title', 'Groups - SpotLite')

@section('links')
    <link rel="stylesheet" href="{{mix('/css/app.css')}}">
@stop

@section('body')
    <div id="sl">
        <default-template v-cloak>
            <default-header slot="default-header"></default-header>
            <default-content slot="default-content">
                <content-header slot="content-header">
                    <span slot="content-header-title">Update Group</span>
                </content-header>
                <content-body slot="content-body"></content-body>
            </default-content>
        </default-template>
    </div>
@stop

@section('scripts')
    <script>
        var editingGroup = JSON.parse('{!! addslashes($group->toJson()) !!}');
    </script>
    <script src="{{mix('/js/app/group/edit.js')}}"></script>
@stop