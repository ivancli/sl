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
    @foreach($products as $product)
        @foreach($product->sites as $site)
            @if($report->show_all == 'y' || $site->has_price_change == true)
                <tr class="{{$site->is_crawl_failed == true ? 'text-danger' : ($site->is_my_site == true ? 'text-tiffany' : '')}}">
                    <td>{{ $product->category->category_name }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>
                        <a href="{{$site->siteUrl}}">
                            {{ $site->displayName }}
                        </a>
                    </td>
                    <td>
                        @if(!is_null($site->item) && !is_null($site->item->recentPrice))
                            <div style="text-align: right">
                                ${{ currency($site->item->recentPrice) }}
                            </div>
                        @else
                            <div class="text-center">
                                &ndash;
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($site->is_crawl_failed == true)
                            <div class="text-center">
                                <img src="{{asset('/images/cross.png')}}" width="10">
                            </div>
                        @else
                            @if($site->has_price_change == true && !is_null($site->item) && !is_null($site->item->recentPrice) && !is_null($site->item->previousPrice))
                                <div class="text-right">
                                    @if($site->item->recentPrice < $site->item->previousPrice)
                                        <img src="{{asset('/images/down.png')}}" width="8">
                                    @else
                                        <img src="{{asset('/images/up.png')}}" width="8">
                                    @endif
                                    ${{ abs(currency($site->item->recentPrice - $site->item->previousPrice)) }}
                                </div>
                            @else
                                <div class="text-center">
                                    &ndash;
                                </div>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($site->is_crawl_failed == true)
                            <div class="text-center">
                                &ndash;
                            </div>
                        @else
                            @if($site->has_price_change == true && !is_null($site->item) && !is_null($site->item->lastChangedAt))
                                {{$site->item->lastChangedAt}}
                            @else
                                <div class="text-center">
                                    &ndash;
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
            @endif
    @endforeach
@endforeach
</tbody>
</table>
@endcomponent



@component('mail::button', ['url' => route('product.index')])
GO TO MY PRODUCTS
@endcomponent


@endcomponent
