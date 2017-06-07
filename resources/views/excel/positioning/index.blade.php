<table>
    <thead>
    <tr>
        <th>Category</th>
        <th>Product</th>
        <th>Brand</th>
        <th>Supplier</th>
        <th>SKU</th>
        <th>Cost price</th>
        <th>Reference site price</th>
        <th>Cheapest site</th>
        <th>Cheapest site URL</th>
        <th>Cheapest $</th>
        <th>Difference $</th>
        <th>Difference %</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->category_name }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->brand }}</td>
            <td>{{ $product->supplier }}</td>
            <td>{{ $product->sku }}</td>
            <td>
                @if(!is_null($product->cost_price))
                    ${{ number_format(floatval($product->cost_price), 2) }}
                @endif
            </td>
            <td>
                @if(isset($product->reference_recent_price) && !is_null($product->reference_recent_price))
                    ${{ number_format($product->reference_recent_price, 2) }}
                @else
                    n/a
                @endif
            </td>
            <th>
                @if(isset($product->cheapest_site_url))
                    @foreach(explode('$ $', $product->cheapest_site_url) as $index=>$cheapestSite)
                        {{ isset(explode('$#$',$cheapestSite)[1]) ? explode('$#$',$cheapestSite)[1] : explode('$#$',$cheapestSite)[0] }}@if(count(explode('$ $', $product->cheapest_site_url)) > 1)<br>@endif
                    @endforeach
                @endif
            </th>
            <td>
                @if(isset($product->cheapest_site_url))
                    @foreach(explode('$ $', $product->cheapest_site_url) as $index=>$cheapestSite)
                        {{ explode('$#$',$cheapestSite)[0] }}@if(count(explode('$ $', $product->cheapest_site_url)) > 1)<br>@endif
                    @endforeach
                @endif
            </td>
            <td>
                @if(isset($product->cheapest_recent_price) && !is_null($product->cheapest_recent_price))
                    ${{ number_format($product->cheapest_recent_price, 2) }}
                @else
                    n/a
                @endif
            </td>
            <td>
                @if(isset($product->reference_recent_price) && !is_null($product->reference_recent_price))
                    @if(floatval($product->reference_recent_price) - floatval($product->cheapest_recent_price) == 0)
                        @if(count(explode('$ $',$product->cheapest_site_url)) > 1)
                            0
                        @else
                            @if(!is_null($product->second_cheapest_recent_price))
                                +${{ number_format(abs($product->cheapest_recent_price - $product->second_cheapest_recent_price), 2) }}
                            @else
                                n/a
                            @endif
                        @endif
                    @else
                        -${{ number_format(abs($product->reference_recent_price - $product->cheapest_recent_price), 2) }}
                    @endif
                @else
                    n/a
                @endif
            </td>
            <td>
                @if(isset($product->reference_recent_price) && !is_null($product->reference_recent_price))
                    @if(floatval($product->cheapest_recent_price) - floatval($product->reference_recent_price) == 0)
                        @if(count(explode('$ $',$product->cheapest_site_url)) > 1)
                            0
                        @else
                            @if(!is_null($product->second_cheapest_recent_price))
                                +{{ number_format(abs($product->cheapest_recent_price - $product->second_cheapest_recent_price) / $product->reference_recent_price * 100, 2) }}
                            @else
                                n/a
                            @endif
                        @endif
                    @else
                        -{{ number_format(abs($product->reference_recent_price - $product->cheapest_recent_price) / $product->reference_recent_price * 100, 2) }}
                    @endif
                @else
                    n/a
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>