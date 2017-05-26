@component('mail::layout')
{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

@slot('lower_body_1')
@component('mail::report_preference')
@endcomponent
@endslot
@slot('lower_body_2')
@component('mail::contact_us')
@endcomponent
@endslot

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
SpotLite - focus on what matters.
<br>
<br>
You've received this email because you are subscribed to SpotLite.
@endcomponent
@endslot
@endcomponent
