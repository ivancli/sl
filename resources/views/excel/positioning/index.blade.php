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
            <td>{{ $product->category->category_name }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->meta->brand }}</td>
            <td>{{ $product->meta->supplier }}</td>
            <td>{{ $product->meta->sku }}</td>
            <td>
                @if(!is_null($product->meta->cost_price))
                    ${{ number_format(floatval($product->meta->cost_price), 2) }}
                @endif
            </td>
            <td>
                @if(isset($product->referencePrice) && !is_null($product->referencePrice))
                    ${{ number_format($product->referencePrice, 2) }}
                @else
                    n/a
                @endif
            </td>
            <th>
                @if(isset($product->cheapestSites))
                    @foreach($product->cheapestSites as $index=>$cheapestSite)
                        {{ $cheapestSite->displayName }}@if(count($product->cheapestSite) > 1)<br>@endif
                    @endforeach
                @endif
            </th>
            <td>
                @if(isset($product->cheapestSites))
                    @foreach($product->cheapestSites as $index=>$cheapestSite)
                        {{ $cheapestSite->siteUrl }}@if(count($product->cheapestSite) > 1)<br>@endif
                    @endforeach
                @endif
            </td>
            <td>
                @if(isset($product->cheapestPrice) && !is_null($product->cheapestPrice))
                    ${{ number_format($product->cheapestPrice, 2) }}
                @else
                    n/a
                @endif
            </td>
            <td>
                @if(!is_null($product->referencePrice))
                    @if(floatval($product->referencePrice) - floatval($product->cheapestPrice) == 0)
                        @if(count($product->cheapestSites) > 1)
                            0
                        @else
                            @if(!is_null($product->secondCheapestPrice))
                                +${{ number_format(abs($product->cheapestPrice - $product->secondCheapestPrice), 2) }}
                            @else
                                n/a
                            @endif
                        @endif
                    @else
                        -${{ number_format(abs($product->referencePrice - $product->cheapestPrice), 2) }}
                    @endif
                @else
                    n/a
                @endif
            </td>
            <td>
                @if(!is_null($product->referencePrice))
                    @if(floatval($product->cheapestPrice) - floatval($product->referencePrice) == 0)
                        @if(count($product->cheapestSites) > 1)
                            0
                        @else
                            @if(!is_null($product->secondCheapestPrice))
                                +{{ number_format(abs($product->cheapestPrice - $product->secondCheapestPrice) / $product->referencePrice * 100, 2) }}
                            @else
                                n/a
                            @endif
                        @endif
                    @else
                        -{{ number_format(abs($product->referencePrice - $product->cheapestPrice) / $product->referencePrice * 100, 2) }}
                    @endif
                @else
                    n/a
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>