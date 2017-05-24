@component('mail::message')

# Hi Ivan

The price for the following product URLs are found to have changed.


@if(isset($sites))
|Category   |Product    |URL    |
|-----------|-----------|-------|
@foreach($sites as $site)
|{{$site->product->category->category_name}}|{{$site->product->product_name}}|{{$site->url->domainFullPath}}|
@endforeach
@endif

You can also view this information through your Products page:

@component('mail::button', ['url' => route('product.index')])
VIEW MY PRODUCTS
@endcomponent

@endcomponent
