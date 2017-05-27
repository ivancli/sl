@component('mail::alert_message')

## Hi {{$user->first_name}},

The price for the product *{{$product->product_name}}* are found to have changed.


@if(isset($sites))
|Category   |Product    |URL    |
|-----------|-----------|-------|
@foreach($sites as $site)
|{{$product->category->category_name}}|{{$product->product_name}}|{{$site->url->domainFullPath}}|
@endforeach
@endif

You can also view this information through your Products page:

@component('mail::button', ['url' => route('product.index')])
VIEW MY PRODUCTS
@endcomponent

@endcomponent
