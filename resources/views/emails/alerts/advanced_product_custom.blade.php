@component('mail::alert_message')

## Hi {{$user->first_name}},

The price for product *{{$product->product_name}}* are found to be
@if($alert->comp_operator == '<')
below
@elseif($alert->comp_operator == '<=')
below or equal to
@elseif($alert->comp_operator == '>')
above
@elseif($alert->comp_operator == '>=')
above or equal to
@elseif($alert->comp_operator == '=')
equal to
@endif
${{currency($alert->comp_price)}}.


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
