@component('mail::alert_message')

## Hi {{$user->first_name}},

The price for the following product URLs are found to have changed.


@if(isset($sites))
@component('mail::table')
|Category   |Product    |Product Page URL    |
|-----------|-----------|--------------------|
@foreach($sites as $index=>$site)
|{{$site->category_name}}|{{$site->product_name}}|@component('mail::link', ['url' => $site->url]){{$site->display_name}}@endcomponent|
@if($index>500)
@break
@endif
@endforeach
@endcomponent
@endif
@if($sites->count() > 500)

......

...

This email cannot display all Product Page URLs.

@endif

You can also view this information through your Products page:

@component('mail::button', ['url' => route('product.index')])
VIEW MY PRODUCTS
@endcomponent


@endcomponent
