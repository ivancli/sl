<table>
    <tr class="header">
        <td>
            <b>Category</b>
        </td>
        <td>
            <b>Product</b>
        </td>
        <td>
            <b>Site Name</b>
        </td>
        <td>
            <b>Product Page URL</b>
        </td>
        <td>
            <b>Current Price</b>
        </td>
        <td>
            <b>Last Updated</b>
        </td>
        <td>
            <b>Previous Price</b>
        </td>
        <td>
            <b>Change Date</b>
        </td>
    </tr>
    @foreach($category->products as $product)
        @foreach($product->sites as $site)
            <tr>
                <td>{{$category->category_name}}</td>
                <td>{{$product->product_name}}</td>
                <td>{{$site->displayName}}</td>
                <td>{{$site->siteUrl}}</td>
                <td>
                    @if(!is_null($site->item) && !is_null($site->item->recentPrice))
                        {{$site->item->recentPrice}}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(!is_null($site->item) && !is_null($site->item->recentPriceAt))
                        {{$site->item->recentPriceAt}}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(!is_null($site->item) && !is_null($site->item->previousPrice))
                        {{$site->item->previousPrice}}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(!is_null($site->item) && !is_null($site->item->lastChangedAt))
                        {{$site->item->lastChangedAt}}
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    @endforeach
</table>