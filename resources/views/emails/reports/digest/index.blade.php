@component('mail::report_message')

## Hi {{$user->first_name}},

{{ $priceChangeCount }} {{ str_plural('site', $priceChangeCount) }} changed their price since the last report. Your current positioning is:

* Cheapest: {{ $cheapestProductCount }} out of {{ $products->count() }} {{ str_plural('product', $products->count()) }}
* Most Expensive: {{ $mostExpensiveProductCount }} out of {{ $products->count() }} {{ str_plural('product', $products->count()) }}
* Crawlers Failed: {{ $crawlFailCount }} Product Page URLs

<div class="legend" style="padding-bottom: 5px; margin-bottom: 5px;">
Legend:
&nbsp;
<img src="{{asset('/images/up.png')}}" width="10">
Price Increase
&nbsp;
<img src="{{asset('/images/down.png')}}" width="10">
Price Decrease
&nbsp;
<img src="{{asset('/images/cross.png')}}" width="10">
Price Check Failed
</div>

@component('mail::table')
<table>
<thead>
<tr>
<th>Category</th>
<th>Product</th>
<th>Product Page URL</th>
<th>Current $</th>
<th>$ Difference</th>
<th>Last Change</th>
</tr>
</thead>
<tbody>
@foreach($products as $index=>$product)
<tr class="{{$product->status == 'crawl_failed' ? 'text-danger' : ($product->is_my_site == 1 ? 'text-tiffany' : '')}}">
<td>{{ $product->category_name }}</td>
<td>{{ $product->product_name }}</td>
<td>
<a href="{{$product->full_path}}">
{{ $product->display_name }}
</a>
</td>
<td>
@if(!is_null($product->recent_price))
<div style="text-align: right">
${{ currency($product->recent_price) }}
</div>
@else
<div class="text-center">
&ndash;
</div>
@endif
</td>
<td>
@if($product->status == 'crawl_failed')
<div class="text-center">
<img src="{{asset('/images/cross.png')}}" width="10">
</div>
@else
@if(!is_null($product->recent_price) && !is_null($product->previous_price))
<div class="text-right">
@if(floatval($product->recent_price) < floatval($product->previous_price))
<img src="{{asset('/images/down.png')}}" width="8">
@else
<img src="{{asset('/images/up.png')}}" width="8">
@endif
${{ abs(currency(floatval($product->recent_price) - floatval($product->previous_price))) }}
</div>
@else
<div class="text-center">
&ndash;
</div>
@endif
@endif
</td>
<td>
@if($product->status == 'crawl_failed')
<div class="text-center">
&ndash;
</div>
@else
@if(!is_null($product->price_changed_at))
{{$product->price_changed_at}}
@else
<div class="text-center">
&ndash;
</div>
@endif
@endif
</td>
</tr>
@if($index>500)
@break
@endif
@endforeach
</tbody>
</table>
@endcomponent

@if($products->count() > 500)

......

...

This email cannot display all Product Page URLs.

@endif

You can also view this information through your Products page:

@component('mail::button', ['url' => route('product.index')])
GO TO MY PRODUCTS
@endcomponent


@endcomponent
