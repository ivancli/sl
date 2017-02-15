@if(auth()->check())
    <script type="text/javascript">
        var user = JSON.parse('{!! auth()->user()->toJson() !!}');
    </script>
@endif