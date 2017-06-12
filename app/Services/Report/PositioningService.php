<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/06/2017
 * Time: 5:55 PM
 */

namespace App\Services\Report;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PositioningService
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function load(array $data = [])
    {
        DB::enableQueryLog();
        $user = auth()->user();
        $productBuilder = DB::table('products')->where('products.user_id', '=', $user->getKey());
        $select = [
            'products.*',
            'categories.*',
        ];

        #region category
        if (array_has($data, 'category') && !is_null(array_get($data, 'category'))) {
            $category_id = array_get($data, 'category');
            $productBuilder->join('categories', function ($join) use ($category_id) {
                $join->on('products.category_id', '=', 'categories.id')->where('categories.id', $category_id);
            });
        } else {
            $productBuilder->join('categories', 'categories.id', '=', 'products.category_id');
        }
        #endregion

        #region brand
        $brandName = array_get($data, 'brand');
        $supplierName = array_get($data, 'supplier');
        $productBuilder->join('product_metas', function ($join) use ($brandName, $supplierName) {
            $join->on('products.id', '=', 'product_metas.product_id');
            if (!is_null($brandName) && !empty($brandName)) {
                $join->where('product_metas.brand', $brandName);
            }
            if (!is_null($supplierName) && !empty($supplierName)) {
                $join->where('product_metas.supplier', '=', $supplierName);
            }
        });
        #endregion

        #region reference

        if (array_has($data, 'reference') && !is_null(array_get($data, 'reference'))) {
            $reference = array_get($data, 'reference');
            $referenceQuery = DB::raw('
            (
                SELECT sites.*, ebays.value, urls.full_path AS site_url, prices.value AS recent_price
                FROM sites 
                LEFT JOIN items ON(sites.item_id=items.id)
                LEFT JOIN item_metas ebays ON(items.id=ebays.item_id AND ebays.element="SELLER_USERNAME")
                LEFT JOIN urls ON(sites.url_id=urls.id)
                LEFT JOIN item_metas prices ON(items.id=prices.item_id AND prices.element="PRICE")
                WHERE ebays.value LIKE "%' . addslashes(urlencode($reference)) . '%"
                OR urls.full_path LIKE "%' . addslashes(urlencode($reference)) . '%"
            ) AS reference
            ');
            $productBuilder->leftJoin($referenceQuery, function ($join) {
                $join->on('reference.product_id', '=', 'products.id');
            });
            $select[] = 'reference.site_url as reference_site_url';
            $select[] = 'reference.recent_price as reference_recent_price';
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4))) as diff_cheapest');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4)) as percent_diff_cheapest');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(expensiveSites.recent_price AS DECIMAL(10, 4))) as diff_expensive');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(expensiveSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4)) as percent_diff_expensive');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4))) as diff_second_cheapest');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4)) as percent_diff_second_cheapest');
            $select[] = DB::raw('IF((CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4))) = 0, CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4)), CAST(cheapestSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4))) as dynamic_diff_price');
            $select[] = DB::raw('IF((CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4))) = 0, (CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4)), (CAST(cheapestSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4))) as percent_dynamic_diff_price');
        }

        #endregion

        $excludeQuery = "";
        $subExcludeQuery = "";
        if (array_has($data, 'exclude') && !is_null(array_get($data, 'exclude'))) {
            $exclude = array_get($data, 'exclude');

            $excludeQuery = " WHERE ";
            $excludeQuery .= " (ebays.value != '" . addslashes(urlencode($exclude)) . "' OR ebays.value IS NULL) ";
            $excludeQuery .= " AND ";
            $excludeQuery .= " urls.full_path NOT LIKE '%" . addslashes(urlencode($exclude)) . "%' ";

            $subExcludeQuery = " WHERE ";
            $subExcludeQuery .= " (ebayMeta.value != '" . addslashes(urlencode($exclude)) . "' OR ebayMeta.value IS NULL) ";
            $subExcludeQuery .= " AND ";
            $subExcludeQuery .= " urls.full_path NOT LIKE '%" . addslashes(urlencode($exclude)) . "%' ";
        }

        #region cheapest site query

        $select[] = 'cheapestSites.site_urls as cheapest_site_url';
        $select[] = 'cheapestSites.recent_price as cheapest_recent_price';


        $cheapestSiteQuery = DB::raw('
        (
            SELECT cheapestPrices.*, group_concat(concat(urls.full_path, \'$#$\', ifnull(concat(\'eBay: \', ebays.value), \'\')) separator \'$ $\') site_urls FROM 
            (
                SELECT product_id, MIN(CAST(priceMeta.value AS DECIMAL(10, 4))) recent_price
            
                FROM sites 
                JOIN urls ON (sites.url_id=urls.id)
                JOIN items ON(sites.item_id=items.id)
                LEFT JOIN item_metas priceMeta ON(items.id=priceMeta.item_id AND priceMeta.element=\'PRICE\')
                LEFT JOIN item_metas ebayMeta ON(items.id=ebayMeta.item_id AND ebayMeta.element=\'SELLER_USERNAME\')
                ' . $subExcludeQuery . '
                GROUP BY product_id
            ) cheapestPrices
            
            LEFT JOIN sites ON (sites.product_id=cheapestPrices.product_id)
            JOIN urls ON (sites.url_id=urls.id)
            JOIN items ON (sites.item_id=items.id)
            JOIN item_metas prices ON (items.id=prices.item_id AND prices.element=\'PRICE\' AND prices.value=cheapestPrices.recent_price)
            LEFT JOIN item_metas ebays ON(ebays.item_id=items.id AND ebays.element=\'SELLER_USERNAME\')
            ' . $excludeQuery . '
            GROUP BY cheapestPrices.product_id
        ) cheapestSites');

        $productBuilder->leftJoin($cheapestSiteQuery, function ($join) {
            $join->on('cheapestSites.product_id', '=', 'products.id');
        });

        $productBuilder->whereNotNull('cheapestSites.recent_price');

        #endregion

        #region expensive site query
        $select[] = 'expensiveSites.site_urls as expensive_site_url';
        $select[] = 'expensiveSites.recent_price as expensive_recent_price';

        $expensiveSiteQuery = DB::raw('
        (
            SELECT cheapestPrices.*, group_concat(concat(urls.full_path, \'$#$\', ifnull(concat(\'eBay: \', ebays.value), \'\')) separator \'$ $\') site_urls FROM 
            (
                SELECT product_id, MAX(CAST(priceMeta.value AS DECIMAL(10, 4))) recent_price
            
                FROM sites 
                JOIN urls ON (sites.url_id=urls.id)
                JOIN items ON(sites.item_id=items.id)
                LEFT JOIN item_metas priceMeta ON(items.id=priceMeta.item_id AND priceMeta.element=\'PRICE\')
                LEFT JOIN item_metas ebayMeta ON(items.id=ebayMeta.item_id AND ebayMeta.element=\'SELLER_USERNAME\')
                ' . $subExcludeQuery . '
                GROUP BY product_id
            ) cheapestPrices
            
            LEFT JOIN sites ON (sites.product_id=cheapestPrices.product_id)
            JOIN urls ON (sites.url_id=urls.id)
            JOIN items ON (sites.item_id=items.id)
            JOIN item_metas prices ON (items.id=prices.item_id AND prices.element=\'PRICE\' AND prices.value=cheapestPrices.recent_price)
            LEFT JOIN item_metas ebays ON(ebays.item_id=items.id AND ebays.element=\'SELLER_USERNAME\')
            ' . $excludeQuery . '
            GROUP BY cheapestPrices.product_id
            
        ) expensiveSites
        ');
        #endregion

        $productBuilder->leftJoin($expensiveSiteQuery, function ($join) {
            $join->on('expensiveSites.product_id', '=', 'products.id');
        });

        #region second cheapest
        $select[] = 'secondCheapestSites.site_urls as second_cheapest_site_url';
        $select[] = 'secondCheapestSites.recent_price as second_cheapest_recent_price';


        $secondCheapestExcludeQuery = "";
        $secondCheapestSubExcludeQuery = "";
        if (array_has($data, 'exclude') && !is_null(array_get($data, 'exclude'))) {
            $exclude = array_get($data, 'exclude');
            $secondCheapestExcludeQuery = " AND ";
            $secondCheapestExcludeQuery .= " (ebays.value != '" . addslashes(urlencode($exclude)) . "') OR ebays.value IS NULL";
            $secondCheapestExcludeQuery .= " AND ";
            $secondCheapestExcludeQuery .= " urls.full_path NOT LIKE '%" . addslashes(urlencode($exclude)) . "%' ";

            $secondCheapestSubExcludeQuery = " WHERE ";
            $secondCheapestSubExcludeQuery .= " (ebays.value != '" . addslashes(urlencode($exclude)) . "' OR ebays.value IS NULL)  ";
            $secondCheapestSubExcludeQuery .= " AND ";
            $secondCheapestSubExcludeQuery .= " urls.full_path NOT LIKE '%" . addslashes(urlencode($exclude)) . "%' ";
        }

        $secondCheapestSiteQuery = DB::raw('
        (
            SELECT    sites.product_id, group_concat(concat(urls.full_path, \'$#$\', ifnull(concat(\'eBay: \', ebays.value), \'\')) separator \'$ $\') site_urls, MIN(CAST(prices.value AS DECIMAL(10, 4))) recent_price 
            FROM      sites
            JOIN urls ON (sites.url_id=urls.id)
            JOIN items ON(sites.item_id=items.id)
            JOIN item_metas prices ON(items.id=prices.item_id AND prices.element=\'PRICE\')
            LEFT JOIN item_metas ebays ON(items.id=ebays.item_id AND ebays.element=\'SELLER_USERNAME\')
            LEFT JOIN (
            
                select product_id, MIN(CAST(prices.value AS DECIMAL(10, 4))) recent_price
                FROM sites
                JOIN urls ON (sites.url_id=urls.id)
                JOIN items ON(sites.item_id=items.id)
                JOIN item_metas prices ON(items.id=prices.item_id AND prices.element=\'PRICE\')
                LEFT JOIN item_metas ebays ON(items.id=ebays.item_id AND ebays.element=\'SELLER_USERNAME\')
                ' . $secondCheapestSubExcludeQuery . '
                GROUP BY product_id
            
            ) AS a ON (sites.product_id=a.product_id) 
            WHERE     prices.value != a.recent_price 
            AND       sites.is_active = \'y\' 
            ' . $secondCheapestExcludeQuery . '
            GROUP BY  product_id
        ) secondCheapestSites
        ');
        $productBuilder->leftJoin($secondCheapestSiteQuery, function ($join) {
            $join->on('secondCheapestSites.product_id', '=', 'products.id');
        });
        #endregion

        #region search
        if (array_has($data, 'search') && !is_null(array_get($data, 'search'))) {
            $keyword = array_get($data, 'search');
            $productBuilder->where(function ($query) use ($keyword, $data) {
                $query->where('product_name', 'LIKE', " %{$keyword} % ")
                    ->orWhere('category_name', 'LIKE', " %{$keyword}%");
                if (array_has($data, 'reference')) {
                    $query->orWhere('reference.recent_price', 'LIKE', " %{$keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))'), 'LIKE', " %{$keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4))'), 'LIKE', " %{$keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)))'), 'LIKE', " %{$keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4))'), 'LIKE', " %{$keyword}%");
                }
                $query->orWhere('cheapestSites.site_urls', 'LIKE', " %{$keyword}%")
                    ->orWhere('cheapestSites.recent_price', 'LIKE', " %{$keyword}%")
                    ->orWhere('cheapestSites.recent_price', 'LIKE', " %{$keyword}%");
            });
        }
        #endregion

        #region position
        if (array_has($data, 'position') && !is_null(array_get($data, 'position'))) {
            switch (array_get($data, 'position')) {
                case "not_cheapest":
                    $productBuilder->where(function ($query) {
                        $query->where(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))'), '!=', 0)
                            ->orWhereNull(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))'));
                    });
                    break;
                case "most_expensive":
                    $productBuilder->where(DB::raw('ABS(CAST(expensiveSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4)))'), '==', 0);
                    break;
                case "cheapest":
                    $productBuilder->where(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))'), '=', 0);
                    break;
                default:
            }
        }
        #endregion

        $productBuilder->select($select);
        $recordTotal = $productBuilder->count();


        $orderColumn = array_get($data, 'orderBy', 'products.id');
        $orderSequence = array_get($data, 'direction', 'asc');

        if ($orderColumn) {
            if ($orderColumn == 'diff_price') {
                if (array_has($data, 'reference') && !is_null(array_get($data, 'reference'))) {
                    $productBuilder = $productBuilder->orderBy('dynamic_diff_price', $orderSequence);
                } else {
                    $productBuilder = $productBuilder->orderBy('categories.category_name', $orderSequence);
                }
            } elseif ($orderColumn == 'diff_percent') {
                if (array_has($data, 'reference') && !is_null(array_get($data, 'reference'))) {
                    $productBuilder = $productBuilder->orderBy('percent_dynamic_diff_price', $orderSequence);
                } else {
                    $productBuilder = $productBuilder->orderBy('categories.category_name', $orderSequence);
                }
            } else {
                $productBuilder = $productBuilder->orderBy($orderColumn, $orderSequence);
            }
        }

        if (array_has($data, 'start') && !is_null(array_get($data, 'start'))) {
            $productBuilder = $productBuilder->skip(array_get($data, 'start'));
        }
        if (array_has($data, 'length') && !is_null(array_get($data, 'length'))) {
            $productBuilder = $productBuilder->take(array_get($data, 'length'));
        }

        $products = $productBuilder->get();

        $data = $products;
        return compact(['data', 'recordTotal', 'recordsFiltered']);
    }

    public function filterOptions()
    {
        $user = auth()->user();
        $domainModels = $user->domains;
        $domains = $domainModels->pluck('domain');

        /*SELECT seller_usernames.*
FROM seller_usernames
JOIN items ON(items.id=seller_usernames.item_id)
JOIN sites ON(sites.item_id=items.id)
JOIN products ON(sites.product_id=products.id)*/
        $query = DB::table('seller_usernames')
            ->join('items', 'items.id', 'seller_usernames.item_id')
            ->join('sites', 'sites.item_id', 'items.id')
            ->join('products', 'sites.product_id', 'products.id')
            ->where('products.user_id', $user->getKey());
        $query->select([
            DB::raw('DISTINCT seller_usernames.value')
        ]);
        $ebaySellerUsernames = $query->get()->pluck('value');

        $categories = $user->categories;

        $metas = $user->products->pluck('meta');
        $brands = $metas->pluck('brand')->filter()->unique();
        $suppliers = $metas->pluck('supplier')->filter()->unique();
        return compact(['domains', 'ebaySellerUsernames', 'categories', 'brands', 'suppliers']);
    }

    public function export(array $data = [])
    {
        $user = auth()->user();
        $productBuilder = DB::table('products')->where('products.user_id', '=', $user->getKey());
        $select = [
            'products.*',
            'categories.*',
            'product_metas.*',
        ];

        #region category
        if (array_has($data, 'category') && !is_null(array_get($data, 'category'))) {
            $category_id = array_get($data, 'category');
            $productBuilder->join('categories', function ($join) use ($category_id) {
                $join->on('products.category_id', '=', 'categories.id')->where('categories.id', $category_id);
            });
        } else {
            $productBuilder->join('categories', 'categories.id', '=', 'products.category_id');
        }
        #endregion

        #region brand
        $brandName = array_get($data, 'brand');
        $supplierName = array_get($data, 'supplier');
        $productBuilder->join('product_metas', function ($join) use ($brandName, $supplierName) {
            $join->on('products.id', '=', 'product_metas.product_id');
            if (!is_null($brandName) && !empty($brandName)) {
                $join->where('product_metas.brand', $brandName);
            }
            if (!is_null($supplierName) && !empty($supplierName)) {
                $join->where('product_metas.supplier', '=', $supplierName);
            }
        });
        #endregion

        #region reference

        if (array_has($data, 'reference') && !is_null(array_get($data, 'reference'))) {
            $reference = array_get($data, 'reference');
            $referenceQuery = DB::raw('
            (
                SELECT sites.*, ebays.value, urls.full_path AS site_url, prices.value AS recent_price
                FROM sites 
                LEFT JOIN items ON(sites.item_id=items.id)
                LEFT JOIN item_metas ebays ON(items.id=ebays.item_id AND ebays.element="SELLER_USERNAME")
                LEFT JOIN urls ON(sites.url_id=urls.id)
                LEFT JOIN item_metas prices ON(items.id=prices.item_id AND prices.element="PRICE")
                WHERE ebays.value LIKE "%' . addslashes(urlencode($reference)) . '%"
                OR urls.full_path LIKE "%' . addslashes(urlencode($reference)) . '%"
            ) AS reference
            ');
            $productBuilder->leftJoin($referenceQuery, function ($join) {
                $join->on('reference.product_id', '=', 'products.id');
            });
            $select[] = 'reference.site_url as reference_site_url';
            $select[] = 'reference.recent_price as reference_recent_price';
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4))) as diff_cheapest');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4)) as percent_diff_cheapest');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(expensiveSites.recent_price AS DECIMAL(10, 4))) as diff_expensive');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(expensiveSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4)) as percent_diff_expensive');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4))) as diff_second_cheapest');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4)) as percent_diff_second_cheapest');
            $select[] = DB::raw('IF((CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4))) = 0, CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4)), CAST(cheapestSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4))) as dynamic_diff_price');
            $select[] = DB::raw('IF((CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4))) = 0, (CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4)), (CAST(cheapestSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4))) as percent_dynamic_diff_price');
        }

        #endregion

        $excludeQuery = "";
        $subExcludeQuery = "";
        if (array_has($data, 'exclude') && !is_null(array_get($data, 'exclude'))) {
            $exclude = array_get($data, 'exclude');

            $excludeQuery = " WHERE ";
            $excludeQuery .= " (ebays.value != '" . addslashes(urlencode($exclude)) . "' OR ebays.value IS NULL) ";
            $excludeQuery .= " AND ";
            $excludeQuery .= " urls.full_path NOT LIKE '%" . addslashes(urlencode($exclude)) . "%' ";

            $subExcludeQuery = " WHERE ";
            $subExcludeQuery .= " (ebayMeta.value != '" . addslashes(urlencode($exclude)) . "' OR ebayMeta.value IS NULL) ";
            $subExcludeQuery .= " AND ";
            $subExcludeQuery .= " urls.full_path NOT LIKE '%" . addslashes(urlencode($exclude)) . "%' ";
        }

        #region cheapest site query

        $select[] = 'cheapestSites.site_urls as cheapest_site_url';
        $select[] = 'cheapestSites.recent_price as cheapest_recent_price';


        $cheapestSiteQuery = DB::raw('
        (
            SELECT cheapestPrices.*, group_concat(concat(urls.full_path, \'$#$\', ifnull(concat(\'eBay: \', ebays.value), \'\')) separator \'$ $\') site_urls FROM 
            (
                SELECT product_id, MIN(CAST(priceMeta.value AS DECIMAL(10, 4))) recent_price
            
                FROM sites 
                JOIN urls ON (sites.url_id=urls.id)
                JOIN items ON(sites.item_id=items.id)
                LEFT JOIN item_metas priceMeta ON(items.id=priceMeta.item_id AND priceMeta.element=\'PRICE\')
                LEFT JOIN item_metas ebayMeta ON(items.id=ebayMeta.item_id AND ebayMeta.element=\'SELLER_USERNAME\')
                ' . $subExcludeQuery . '
                GROUP BY product_id
            ) cheapestPrices
            
            LEFT JOIN sites ON (sites.product_id=cheapestPrices.product_id)
            JOIN urls ON (sites.url_id=urls.id)
            JOIN items ON (sites.item_id=items.id)
            JOIN item_metas prices ON (items.id=prices.item_id AND prices.element=\'PRICE\' AND prices.value=cheapestPrices.recent_price)
            LEFT JOIN item_metas ebays ON(ebays.item_id=items.id AND ebays.element=\'SELLER_USERNAME\')
            ' . $excludeQuery . '
            GROUP BY cheapestPrices.product_id
        ) cheapestSites');

        $productBuilder->leftJoin($cheapestSiteQuery, function ($join) {
            $join->on('cheapestSites.product_id', '=', 'products.id');
        });

        $productBuilder->whereNotNull('cheapestSites.recent_price');

        #endregion

        #region expensive site query
        $select[] = 'expensiveSites.site_urls as expensive_site_url';
        $select[] = 'expensiveSites.recent_price as expensive_recent_price';

        $expensiveSiteQuery = DB::raw('
        (
            SELECT cheapestPrices.*, group_concat(concat(urls.full_path, \'$#$\', ifnull(concat(\'eBay: \', ebays.value), \'\')) separator \'$ $\') site_urls FROM 
            (
                SELECT product_id, MAX(CAST(priceMeta.value AS DECIMAL(10, 4))) recent_price
            
                FROM sites 
                JOIN urls ON (sites.url_id=urls.id)
                JOIN items ON(sites.item_id=items.id)
                LEFT JOIN item_metas priceMeta ON(items.id=priceMeta.item_id AND priceMeta.element=\'PRICE\')
                LEFT JOIN item_metas ebayMeta ON(items.id=ebayMeta.item_id AND ebayMeta.element=\'SELLER_USERNAME\')
                ' . $subExcludeQuery . '
                GROUP BY product_id
            ) cheapestPrices
            
            LEFT JOIN sites ON (sites.product_id=cheapestPrices.product_id)
            JOIN urls ON (sites.url_id=urls.id)
            JOIN items ON (sites.item_id=items.id)
            JOIN item_metas prices ON (items.id=prices.item_id AND prices.element=\'PRICE\' AND prices.value=cheapestPrices.recent_price)
            LEFT JOIN item_metas ebays ON(ebays.item_id=items.id AND ebays.element=\'SELLER_USERNAME\')
            ' . $excludeQuery . '
            GROUP BY cheapestPrices.product_id
            
        ) expensiveSites
        ');
        #endregion

        $productBuilder->leftJoin($expensiveSiteQuery, function ($join) {
            $join->on('expensiveSites.product_id', '=', 'products.id');
        });

        #region second cheapest
        $select[] = 'secondCheapestSites.site_urls as second_cheapest_site_url';
        $select[] = 'secondCheapestSites.recent_price as second_cheapest_recent_price';


        $secondCheapestExcludeQuery = "";
        $secondCheapestSubExcludeQuery = "";
        if (array_has($data, 'exclude') && !is_null(array_get($data, 'exclude'))) {
            $exclude = array_get($data, 'exclude');
            $secondCheapestExcludeQuery = " AND ";
            $secondCheapestExcludeQuery .= " (ebays.value != '" . addslashes(urlencode($exclude)) . "') OR ebays.value IS NULL";
            $secondCheapestExcludeQuery .= " AND ";
            $secondCheapestExcludeQuery .= " urls.full_path NOT LIKE '%" . addslashes(urlencode($exclude)) . "%' ";

            $secondCheapestSubExcludeQuery = " WHERE ";
            $secondCheapestSubExcludeQuery .= " (ebays.value != '" . addslashes(urlencode($exclude)) . "' OR ebays.value IS NULL)  ";
            $secondCheapestSubExcludeQuery .= " AND ";
            $secondCheapestSubExcludeQuery .= " urls.full_path NOT LIKE '%" . addslashes(urlencode($exclude)) . "%' ";
        }

        $secondCheapestSiteQuery = DB::raw('
        (
            SELECT    sites.product_id, group_concat(concat(urls.full_path, \'$#$\', ifnull(concat(\'eBay: \', ebays.value), \'\')) separator \'$ $\') site_urls, MIN(CAST(prices.value AS DECIMAL(10, 4))) recent_price 
            FROM      sites
            JOIN urls ON (sites.url_id=urls.id)
            JOIN items ON(sites.item_id=items.id)
            JOIN item_metas prices ON(items.id=prices.item_id AND prices.element=\'PRICE\')
            LEFT JOIN item_metas ebays ON(items.id=ebays.item_id AND ebays.element=\'SELLER_USERNAME\')
            LEFT JOIN (
            
                select product_id, MIN(CAST(prices.value AS DECIMAL(10, 4))) recent_price
                FROM sites
                JOIN urls ON (sites.url_id=urls.id)
                JOIN items ON(sites.item_id=items.id)
                JOIN item_metas prices ON(items.id=prices.item_id AND prices.element=\'PRICE\')
                LEFT JOIN item_metas ebays ON(items.id=ebays.item_id AND ebays.element=\'SELLER_USERNAME\')
                ' . $secondCheapestSubExcludeQuery . '
                GROUP BY product_id
            
            ) AS a ON (sites.product_id=a.product_id) 
            WHERE     prices.value != a.recent_price 
            AND       sites.is_active = \'y\' 
            ' . $secondCheapestExcludeQuery . '
            GROUP BY  product_id
        ) secondCheapestSites
        ');
        $productBuilder->leftJoin($secondCheapestSiteQuery, function ($join) {
            $join->on('secondCheapestSites.product_id', '=', 'products.id');
        });
        #endregion

        #region search
        if (array_has($data, 'search') && !is_null(array_get($data, 'search'))) {
            $keyword = array_get($data, 'search');
            $productBuilder->where(function ($query) use ($keyword, $data) {
                $query->where('product_name', 'LIKE', " %{$keyword} % ")
                    ->orWhere('category_name', 'LIKE', " %{
                $keyword}%");
                if (array_has($data, 'reference')) {
                    $query->orWhere('reference.recent_price', 'LIKE', " %{
                $keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))'), 'LIKE', " %{
                $keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4))'), 'LIKE', " %{
                $keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)))'), 'LIKE', " %{
                $keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 4)))/CAST(reference.recent_price AS DECIMAL(10, 4))'), 'LIKE', " %{
                $keyword}%");
                }
                $query->orWhere('cheapestSites.site_urls', 'LIKE', " %{
                $keyword}%")
                    ->orWhere('cheapestSites.recent_price', 'LIKE', " %{
                $keyword}%")
                    ->orWhere('cheapestSites.recent_price', 'LIKE', " %{
                $keyword}%");
            });
        }
        #endregion

        #region position
        if (array_has($data, 'position') && !is_null(array_get($data, 'position'))) {
            switch (array_get($data, 'position')) {
                case "not_cheapest":
                    $productBuilder->where(function ($query) {
                        $query->where(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))'), '!=', 0)
                            ->orWhereNull(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))'));
                    });
                    break;
                case "most_expensive":
                    $productBuilder->where(DB::raw('ABS(CAST(expensiveSites.recent_price AS DECIMAL(10, 4)) - CAST(reference.recent_price AS DECIMAL(10, 4)))'), '==', 0);
                    break;
                case "cheapest":
                    $productBuilder->where(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 4)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 4)))'), '=', 0);
                    break;
                default:
            }
        }
        #endregion

        $productBuilder->select($select);


        $orderColumn = array_get($data, 'orderBy', 'products.id');
        $orderSequence = array_get($data, 'direction', 'asc');

        if ($orderColumn) {
            if ($orderColumn == 'diff_price') {
                if (array_has($data, 'reference') && !is_null(array_get($data, 'reference'))) {
                    $productBuilder = $productBuilder->orderBy('dynamic_diff_price', $orderSequence);
                } else {
                    $productBuilder = $productBuilder->orderBy('categories.category_name', $orderSequence);
                }
            } elseif ($orderColumn == 'diff_percent') {
                if (array_has($data, 'reference') && !is_null(array_get($data, 'reference'))) {
                    $productBuilder = $productBuilder->orderBy('percent_dynamic_diff_price', $orderSequence);
                } else {
                    $productBuilder = $productBuilder->orderBy('categories.category_name', $orderSequence);
                }
            } else {
                $productBuilder = $productBuilder->orderBy($orderColumn, $orderSequence);
            }
        }

        $products = $productBuilder->get();
        $fileName = "export_positioning_" . Carbon::now()->format('YmdHis');
        Excel::create($fileName, function ($excel) use ($products) {
            $excel->sheet('positioning view', function ($sheet) use ($products) {
                $sheet->loadView('excel.positioning.index', compact(['products', 'position']));
            });
        })->download('csv');
    }
}