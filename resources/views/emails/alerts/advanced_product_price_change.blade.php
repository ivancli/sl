@component('mail::alert_message')

## Hi {{$user->first_name}},

The price for the product *{{$product->product_name}}* are found to have changed.


@if(isset($sites))
@component('mail::table')
|Category   |Product    |Product Page URL    |
|-----------|-----------|--------------------|
@foreach($sites as $site)
|{{$product->category->category_name}}|{{$product->product_name}}|@component('mail::link', ['url' => $site->siteUrl]){{$site->displayName}}@endcomponent|
@endforeach
@endcomponent
@endif

You can also view this information through your Products page:

@component('mail::button', ['url' => route('product.index')])
VIEW MY PRODUCTS
@endcomponent

@endcomponent
