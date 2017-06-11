@component('mail::alert_message')

## Hi {{$user->first_name}},

The price for the category *{{$category->category_name}}* are found to have beaten your prices.


@if(isset($products))
@component('mail::table')
|Category   |Product    |
|-----------|-----------|
@foreach($products as $index=>$product)
|{{$product->category_name}}|{{$product->product_name}}
@if($index>500)
@break
@endif
@endforeach
@endcomponent
@endif
@if($products->count() > 500)

......

...

This email cannot display all Products.

@endif

You can also view this information through your Products page:

@component('mail::button', ['url' => route('product.index')])
VIEW MY PRODUCTS
@endcomponent


@endcomponent
