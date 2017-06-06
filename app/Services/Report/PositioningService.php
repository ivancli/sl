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
                LEFT JOIN item_metas ebays ON(items.id=ebays.id AND ebays.element="SELLER_USERNAME")
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
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 6))) as diff_cheapest');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 6)))/CAST(reference.recent_price AS DECIMAL(10, 6)) as percent_diff_cheapest');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(expensiveSites.recent_price AS DECIMAL(10, 6))) as diff_expensive');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(expensiveSites.recent_price AS DECIMAL(10, 6)))/CAST(reference.recent_price AS DECIMAL(10, 6)) as percent_diff_expensive');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 6))) as diff_second_cheapest');
            $select[] = DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 6)))/CAST(reference.recent_price AS DECIMAL(10, 6)) as percent_diff_second_cheapest');
            $select[] = DB::raw('IF((CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 6))) = 0, CAST(secondCheapestSites.recent_price AS DECIMAL(10, 6)) - CAST(reference.recent_price AS DECIMAL(10, 6)), CAST(cheapestSites.recent_price AS DECIMAL(10, 6)) - CAST(reference.recent_price AS DECIMAL(10, 6))) as dynamic_diff_price');
            $select[] = DB::raw('IF((CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 6))) = 0, (CAST(secondCheapestSites.recent_price AS DECIMAL(10, 6)) - CAST(reference.recent_price AS DECIMAL(10, 6)))/CAST(reference.recent_price AS DECIMAL(10, 6)), (CAST(cheapestSites.recent_price AS DECIMAL(10, 6)) - CAST(reference.recent_price AS DECIMAL(10, 6)))/CAST(reference.recent_price AS DECIMAL(10, 6))) as percent_dynamic_diff_price');
        }

        #endregion


        #region cheapest site query

        $select[] = 'cheapestSites.site_urls as cheapest_site_url';
        $select[] = 'cheapestSites.recent_price as cheapest_recent_price';

        $cheapestSiteQuery = DB::raw('
        (
            SELECT cheapestPrices.*, group_concat(concat(urls.full_path, \'$#$\', ifnull(concat(\'eBay: \', ebays.value), \'\')) separator \'$ $\') site_urls FROM 
            (
                SELECT product_id, MIN(CAST(priceMeta.value AS DECIMAL(10, 6))) recent_price
            
                FROM sites JOIN items ON(sites.item_id=items.id)
                
                LEFT JOIN item_metas priceMeta ON(items.id=priceMeta.item_id AND priceMeta.element=\'PRICE\')
                LEFT JOIN item_metas ebayMeta ON(items.id=ebayMeta.item_id AND ebayMeta.element=\'SELLER_USERNAME\')
                
                GROUP BY product_id
            ) cheapestPrices
            
            LEFT JOIN sites ON (sites.product_id=cheapestPrices.product_id)
            JOIN urls ON (sites.url_id=urls.id)
            JOIN items ON (sites.item_id=items.id)
            JOIN item_metas prices ON (items.id=prices.item_id AND prices.element=\'PRICE\' AND prices.value=cheapestPrices.recent_price)
            LEFT JOIN item_metas ebays ON(ebays.item_id=items.id AND ebays.element=\'SELLER_USERNAME\')
            
            GROUP BY cheapestPrices.product_id
        ) cheapestSites');

        $productBuilder->leftJoin($cheapestSiteQuery, function ($join) {
            $join->on('cheapestSites.product_id', '=', 'products.id');
        });
        #endregion

        #region expensive site query
        $select[] = 'expensiveSites.site_urls as expensive_site_url';
        $select[] = 'expensiveSites.recent_price as expensive_recent_price';

        $expensiveSiteQuery = DB::raw('
        (
            SELECT cheapestPrices.*, group_concat(concat(urls.full_path, \'$#$\', ifnull(concat(\'eBay: \', ebays.value), \'\')) separator \'$ $\') site_urls FROM 
            (
                SELECT product_id, MAX(CAST(priceMeta.value AS DECIMAL(10, 6))) recent_price
            
                FROM sites JOIN items ON(sites.item_id=items.id)
                
                LEFT JOIN item_metas priceMeta ON(items.id=priceMeta.item_id AND priceMeta.element=\'PRICE\')
                LEFT JOIN item_metas ebayMeta ON(items.id=ebayMeta.item_id AND ebayMeta.element=\'SELLER_USERNAME\')
                
                GROUP BY product_id
            ) cheapestPrices
            
            LEFT JOIN sites ON (sites.product_id=cheapestPrices.product_id)
            JOIN urls ON (sites.url_id=urls.id)
            JOIN items ON (sites.item_id=items.id)
            JOIN item_metas prices ON (items.id=prices.item_id AND prices.element=\'PRICE\' AND prices.value=cheapestPrices.recent_price)
            LEFT JOIN item_metas ebays ON(ebays.item_id=items.id AND ebays.element=\'SELLER_USERNAME\')
            
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

        $secondCheapestSiteQuery = DB::raw('
        (
            SELECT    sites.product_id, group_concat(concat(urls.full_path, \'$#$\', ifnull(concat(\'eBay: \', ebays.value), \'\')) separator \'$ $\') site_urls, MIN(CAST(prices.value AS DECIMAL(10, 6))) recent_price 
            FROM      sites 
            JOIN items ON(sites.item_id=items.id)
            JOIN item_metas prices ON(items.id=prices.item_id AND prices.element=\'PRICE\')
            LEFT JOIN item_metas ebays ON(items.id=ebays.item_id AND ebays.element=\'SELLER_USERNAME\')
            JOIN urls ON(sites.url_id=urls.id)
            LEFT JOIN (
            
                select product_id, MIN(CAST(item_metas.value AS DECIMAL(10, 6))) recent_price
                FROM sites 
                JOIN items ON(sites.item_id=items.id)
                JOIN item_metas ON(items.id=item_metas.item_id AND item_metas.element=\'PRICE\')
                GROUP BY product_id
            
            ) AS a ON (sites.product_id=a.product_id) 
            WHERE     prices.value != a.recent_price 
            AND       sites.is_active = \'y\' 
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
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 6)))'), 'LIKE', " %{
                $keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 6)))/CAST(reference.recent_price AS DECIMAL(10, 6))'), 'LIKE', " %{
                $keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 6)))'), 'LIKE', " %{
                $keyword}%");
                    $query->orWhere(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(secondCheapestSites.recent_price AS DECIMAL(10, 6)))/CAST(reference.recent_price AS DECIMAL(10, 6))'), 'LIKE', " %{
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
                        $query->where(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 6)))'), '!=', 0)
                            ->orWhereNull(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 6)))'));
                    });
                    break;
                case "most_expensive":
                    $productBuilder->where(DB::raw('ABS(CAST(expensiveSites.recent_price AS DECIMAL(10, 6)) - CAST(reference.recent_price AS DECIMAL(10, 6)))'), '==', 0);
                    break;
                case "cheapest":
                    $productBuilder->where(DB::raw('ABS(CAST(reference.recent_price AS DECIMAL(10, 6)) - CAST(cheapestSites.recent_price AS DECIMAL(10, 6)))'), '=', 0);
                    break;
                default:
            }
        }
        #endregion

        $productBuilder->select($select);
        $recordTotal = $user->products()->count();
        $recordsFiltered = $productBuilder->count();


        $orderColumn = array_get($data, 'orderBy', 'products.id');
        $orderSequence = array_get($data, 'direction', 'asc');

        if ($orderColumn) {
            if ($orderColumn == 'diff_ref_cheapest') {
                if (array_has($data, 'reference') && !is_null(array_get($data, 'reference'))) {
                    $productBuilder = $productBuilder->orderBy('dynamic_diff_price', $orderSequence);
                } else {
                    $productBuilder = $productBuilder->orderBy('categories.category_name', $orderSequence);
                }
            } elseif ($orderColumn == 'percent_diff_ref_cheapest') {
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

//    public function load(array $data = [])
//    {
//        $user = auth()->user();
//
//
//        $length = array_get($data, 'per_page', 25);
//        $orderByColumn = array_get($data, 'orderBy', 'id');
//        $orderByDirection = array_get($data, 'direction', 'asc');
//        $position = array_get($data, 'position');
//        $key = array_get($data, 'key');
//        $brand = array_get($data, 'brand');
//        $supplier = array_get($data, 'supplier');
//        $category = array_get($data, 'category');
//        $page = array_get($data, 'page');
//        $perPage = array_get($data, 'per_page');
//        $productsBuilder = $user->products();
//        $productsBuilder->with('sites.item', 'category');
//        $products = $productsBuilder->get();
//
//        $total = $products->count();
//
//        $products = $products->filter(function ($product) {
//            return $product->sites->count() > 1;
//        });
//
//
//        if (!is_null($brand)) {
//            $products = $products->filter(function ($product) use ($brand) {
//                return $product->meta->brand == $brand;
//            });
//        }
//        if (!is_null($supplier)) {
//            $products = $products->filter(function ($product) use ($supplier) {
//                return $product->meta->supplier == $supplier;
//            });
//        }
//
//        $products->each(function ($product) use ($data, $user) {
//            if (array_has($data, 'exclude') && !is_null(array_get($data, 'exclude'))) {
////                foreach (array_get($data, 'exclude') as $exclude) {
//                $exclude = array_get($data, 'exclude');
//                $product->sites = $product->sites->filter(function ($site) use ($exclude) {
//                    if (!is_null($site->item)) {
//                        if ($site->item->sellerUsername != $exclude) {
//                            if (!str_contains($site->url->domainFullPath, $exclude)) {
//                                return true;
//                            }
//                        }
//                    }
//                    return false;
//                });
////                }
//            }
//
//            $product->sites = $product->sites->filter(function ($site) {
//                return !is_null($site->item);
//            });
//
//
//            $reference = array_get($data, 'reference');
//            $referenceSite = null;
//            $product->setAttribute('referencePrice', null);
//            if (!is_null($reference)) {
//                $referenceSite = $product->sites->filter(function ($site) use ($reference) {
//                    if (!is_null($site->item->sellerUsername)) {
//                        if ($site->item->sellerUsername == $reference) {
//                            return true;
//                        }
//                    }
//                    return str_contains($site->siteUrl, $reference);
//                })->first();
//                if (!is_null($referenceSite) && !is_null($referenceSite->item)) {
//                    $product->setAttribute('referencePrice', $referenceSite->item->recentPrice);
//                }
//            }
//
//            $cheapestPrice = $product->sites->min(function ($site) {
//                if (!is_null($site->item->recentPrice)) {
//                    return $site->item->recentPrice;
//                }
//                return true;
//            });
//            $secondCheapestPrice = $product->sites->filter(function ($site) use ($cheapestPrice) {
//                if (!is_null($site->item->recentPrice)) {
//                    return $site->item->recentPrice != $cheapestPrice;
//                }
//                return false;
//            })->min(function ($site) {
//                if (!is_null($site->item->recentPrice)) {
//                    return $site->item->recentPrice;
//                }
//                return true;
//            });
//
//            $mostExpensivePrice = $product->sites->max(function ($site) {
//                if (!is_null($site->item->recentPrice)) {
//                    return $site->item->recentPrice;
//                }
//                return false;
//            });
//
//            $cheapestSites = $product->sites->filter(function ($site) use ($cheapestPrice) {
//                return $site->item->recentPrice == $cheapestPrice;
//            });
//
//            $mostExpensiveSites = $product->sites->filter(function ($site) use ($mostExpensivePrice) {
//                return $site->item->recentPrice == $mostExpensivePrice;
//            });
//
//            $product->setAttribute('referenceSite', $referenceSite);
//            $product->setAttribute('cheapestPrice', $cheapestPrice);
//            $product->setAttribute('secondCheapestPrice', $secondCheapestPrice);
//            $product->setAttribute('mostExpensivePrice', $mostExpensivePrice);
//            $product->setAttribute('cheapestSites', $cheapestSites);
//            $product->setAttribute('mostExpensiveSites', $mostExpensiveSites);
//        });
//        switch ($position) {
//            case 'not_cheapest':
//                $products = $products->filter(function ($product) use ($position) {
//                    if (!is_null($product->referenceSite)) {
//                        return $product->referencePrice != $product->cheapestPrice;
//                    }
//                });
//                break;
//            case 'most_expensive':
//                $products = $products->filter(function ($product) use ($position) {
//                    if (!is_null($product->referenceSite)) {
//                        return $product->referencePrice == $product->mostExpensivePrice;
//                    }
//                });
//                break;
//            case 'cheapest':
//                $products = $products->filter(function ($product) use ($position) {
//                    if (!is_null($product->referenceSite)) {
//                        return $product->referencePrice == $product->cheapestPrice;
//                    }
//                });
//                break;
//        }
//
//        if (!is_null($category) && !empty($category)) {
//            $products = $products->filter(function ($product) use ($category) {
//                return $product->category_id == $category;
//            });
//        }
//
//        if (!is_null($key) && !empty($key)) {
//            $products->filter(function ($product) use ($key) {
//                return str_contains($product->category->cateogry_name, $key) ||
//                    str_contains($product->product_name, $key) ||
//                    $product->cheapestSites->filter(function ($site) use ($key) {
//                        return str_contains($site->siteUrl, $key);
//                    })->count() > 0 ||
//                    str_contains($product->cheapestPrice, $key);
//            });
//        }
//
//        switch ($orderByDirection) {
//            case 'asc':
//                switch ($orderByColumn) {
//                    case 'category_name':
//                        $products = $products->sortBy(function ($product) {
//                            return $product->category->category_name;
//                        });
//                        break;
//                    case 'product_name':
//                        $products = $products->sortBy(function ($product) {
//                            return $product->product_name;
//                        });
//                        break;
//                    case 'ref_price':
//                        $products = $products->sortBy(function ($product) {
//                            return $product->referencePrice;
//                        });
//                        break;
//                    case 'cheapest':
//                        $products = $products->sortBy(function ($product) {
//                            return $product->referencePrice == $product->cheapestPrice;
//                        });
//                        break;
//                    case 'cheapest_price':
//                        $products = $products->sortBy(function ($product) {
//                            return $product->cheapestPrice;
//                        });
//                        break;
//                    case 'diff_price':
//                        $products = $products->sortBy(function ($product) {
//                            if (!is_null($product->referencePrice) && !is_null($product->cheapestPrice)) {
//                                return $product->referencePrice - $product->cheapestPrice;
//                            }
//                            return 0;
//                        });
//                        break;
//                    case 'diff_percent':
//                        $products = $products->sortBy(function ($product) {
//                            if (!is_null($product->referencePrice) && !is_null($product->cheapestPrice)) {
//                                return ($product->referencePrice - $product->cheapestPrice) / $product->referencePrice;
//                            }
//                            return 0;
//                        });
//                        break;
//                }
//                break;
//            case 'desc':
//                switch ($orderByColumn) {
//                    case 'category_name':
//                        $products = $products->sortByDesc(function ($product) {
//                            return $product->category->category_name;
//                        });
//                        break;
//                    case 'product_name':
//                        $products = $products->sortByDesc(function ($product) {
//                            return $product->product_name;
//                        });
//                        break;
//                    case 'ref_price':
//                        $products = $products->sortByDesc(function ($product) {
//                            return $product->referencePrice;
//                        });
//                        break;
//                    case 'cheapest':
//                        $products = $products->sortByDesc(function ($product) {
//                            return $product->referencePrice == $product->cheapestPrice;
//                        });
//                        break;
//                    case 'cheapest_price':
//                        $products = $products->sortByDesc(function ($product) {
//                            return $product->cheapestPrice;
//                        });
//                        break;
//                    case 'diff_price':
//                        $products = $products->sortByDesc(function ($product) {
//                            if (!is_null($product->referencePrice) && !is_null($product->cheapestPrice)) {
//                                return $product->referencePrice - $product->cheapestPrice;
//                            }
//                            return 0;
//                        });
//                        break;
//                    case 'diff_percent':
//                        $products = $products->sortByDesc(function ($product) {
//                            if (!is_null($product->referencePrice) && !is_null($product->cheapestPrice)) {
//                                return ($product->referencePrice - $product->cheapestPrice) / $product->referencePrice;
//                            }
//                            return 0;
//                        });
//                        break;
//                }
//                break;
//        }
//        $skip = ($page - 1) * $perPage;
//        if ($skip > 0) {
//            $products = $products->slice($skip);
//        }
//        if (!is_null($length) && !empty($length)) {
//            $products = $products->take($length);
//        }
//
//        $output = new \stdClass();
//        $output->data = $products;
//        $output->current_page = $page;
//        $output->last_page = ceil($total / $perPage);
//        $output->next_page = ceil($total / $perPage) > $page + 1 ? $page + 1 : null;
//        $output->prev_page = $page - 1 > 0 ? $page : null;
//
//        return $output;
//    }

    public function filterOptions()
    {
        $user = auth()->user();
        $domainModels = $user->domains;
        $domains = $domainModels->pluck('domain');
        $sites = $user->sites()->with('item')->get();
        $items = $sites->pluck('item');
        $ebaySellerUsernames = $items->pluck('sellerUsername')->filter()->unique();
        $categories = $user->categories;

        $metas = $user->products->pluck('meta');
        $brands = $metas->pluck('brand')->filter()->unique();
        $suppliers = $metas->pluck('supplier')->filter()->unique();
        return compact(['domains', 'ebaySellerUsernames', 'categories', 'brands', 'suppliers']);
    }

    public function export(array $data = [])
    {

        $user = auth()->user();


        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $position = array_get($data, 'position');
        $key = array_get($data, 'key');
        $brand = array_get($data, 'brand');
        $supplier = array_get($data, 'supplier');
        $category = array_get($data, 'category');
        $page = array_get($data, 'page');
        $perPage = array_get($data, 'per_page');

        $productsBuilder = $user->products();
        $productsBuilder->with('sites.item', 'category');
        $products = $productsBuilder->get();

        $total = $products->count();

        $products = $products->filter(function ($product) {
            return $product->sites->count() > 1;
        });


        if (!is_null($brand)) {
            $products = $products->filter(function ($product) use ($brand) {
                return $product->meta->brand == $brand;
            });
        }
        if (!is_null($supplier)) {
            $products = $products->filter(function ($product) use ($supplier) {
                return $product->meta->supplier == $supplier;
            });
        }

        $products->each(function ($product) use ($data, $user) {
            if (array_has($data, 'exclude') && !is_null(array_get($data, 'exclude'))) {
//                foreach (array_get($data, 'exclude') as $exclude) {
                $exclude = array_get($data, 'exclude');
                $product->sites = $product->sites->filter(function ($site) use ($exclude) {
                    if (!is_null($site->item)) {
                        if ($site->item->sellerUsername != $exclude) {
                            if (!str_contains($site->url->domainFullPath, $exclude)) {
                                return true;
                            }
                        }
                    }
                    return false;
                });
//                }
            }

            $product->sites = $product->sites->filter(function ($site) {
                return !is_null($site->item);
            });

            $product->sites->each(function ($site) use ($user) {
                if (!is_null($site->item->sellerUsername)) {
                    $site->setAttribute('displayName', $site->item->sellerUsername);
                } else {
                    $matchedDomain = $user->domains->filter(function ($domain) use ($site) {
                        return str_contains($site->url->domainFullPath, $domain->domain) && !is_null($domain->alias);
                    })->first();
                    if (!is_null($matchedDomain)) {
                        $site->setAttribute('displayName', $matchedDomain->alias);
                    } else {
                        $site->setAttribute('displayName', $site->url->domainFullPath);
                    }
                }
            });

            $reference = array_get($data, 'reference');
            $referenceSite = null;
            $product->setAttribute('referencePrice', null);
            if (!is_null($reference)) {
                $referenceSite = $product->sites->filter(function ($site) use ($reference) {
                    if (!is_null($site->item->sellerUsername)) {
                        if ($site->item->sellerUsername == $reference) {
                            return true;
                        }
                    }
                    return str_contains($site->siteUrl, $reference);
                })->first();
                $product->setAttribute('referencePrice', $referenceSite->item->recentPrice);
            }

            $cheapestPrice = $product->sites->min(function ($site) {
                if (!is_null($site->item->recentPrice)) {
                    return $site->item->recentPrice;
                }
                return true;
            });
            $secondCheapestPrice = $product->sites->filter(function ($site) use ($cheapestPrice) {
                if (!is_null($site->item->recentPrice)) {
                    return $site->item->recentPrice != $cheapestPrice;
                }
                return false;
            })->min(function ($site) {
                if (!is_null($site->item->recentPrice)) {
                    return $site->item->recentPrice;
                }
                return true;
            });

            $mostExpensivePrice = $product->sites->max(function ($site) {
                if (!is_null($site->item->recentPrice)) {
                    return $site->item->recentPrice;
                }
                return false;
            });

            $cheapestSites = $product->sites->filter(function ($site) use ($cheapestPrice) {
                return $site->item->recentPrice == $cheapestPrice;
            });

            $mostExpensiveSites = $product->sites->filter(function ($site) use ($mostExpensivePrice) {
                return $site->item->recentPrice == $mostExpensivePrice;
            });

            $product->setAttribute('referenceSite', $referenceSite);
            $product->setAttribute('cheapestPrice', $cheapestPrice);
            $product->setAttribute('secondCheapestPrice', $secondCheapestPrice);
            $product->setAttribute('mostExpensivePrice', $mostExpensivePrice);
            $product->setAttribute('cheapestSites', $cheapestSites);
            $product->setAttribute('mostExpensiveSites', $mostExpensiveSites);
        });
        switch ($position) {
            case 'not_cheapest':
                $products = $products->filter(function ($product) use ($position) {
                    if (!is_null($product->referenceSite)) {
                        return $product->referencePrice != $product->cheapestPrice;
                    }
                });
                break;
            case 'most_expensive':
                $products = $products->filter(function ($product) use ($position) {
                    if (!is_null($product->referenceSite)) {
                        return $product->referencePrice == $product->mostExpensivePrice;
                    }
                });
                break;
            case 'cheapest':
                $products = $products->filter(function ($product) use ($position) {
                    if (!is_null($product->referenceSite)) {
                        return $product->referencePrice == $product->cheapestPrice;
                    }
                });
                break;
        }

        if (!is_null($category) && !empty($category)) {
            $products = $products->filter(function ($product) use ($category) {
                return $product->category_id == $category;
            });
        }

        if (!is_null($key) && !empty($key)) {
            $products->filter(function ($product) use ($key) {
                return str_contains($product->category->cateogry_name, $key) ||
                    str_contains($product->product_name, $key) ||
                    $product->cheapestSites->filter(function ($site) use ($key) {
                        return str_contains($site->siteUrl, $key);
                    })->count() > 0 ||
                    str_contains($product->cheapestPrice, $key);
            });
        }
        switch ($orderByDirection) {
            case 'asc':
                switch ($orderByColumn) {
                    case 'category_name':
                        $products = $products->sortBy(function ($product) {
                            return $product->category->category_name;
                        });
                        break;
                    case 'product_name':
                        $products = $products->sortBy(function ($product) {
                            return $product->product_name;
                        });
                        break;
                    case 'ref_price':
                        $products = $products->sortBy(function ($product) {
                            return $product->referencePrice;
                        });
                        break;
                    case 'cheapest':
                        $products = $products->sortBy(function ($product) {
                            return $product->referencePrice == $product->cheapestPrice;
                        });
                        break;
                    case 'cheapest_price':
                        $products = $products->sortBy(function ($product) {
                            return $product->cheapestPrice;
                        });
                        break;
                    case 'diff_price':
                        $products = $products->sortBy(function ($product) {
                            if (!is_null($product->referencePrice) && !is_null($product->cheapestPrice)) {
                                return $product->referencePrice - $product->cheapestPrice;
                            }
                            return 0;
                        });
                        break;
                    case 'diff_percent':
                        $products = $products->sortBy(function ($product) {
                            if (!is_null($product->referencePrice) && !is_null($product->cheapestPrice)) {
                                return ($product->referencePrice - $product->cheapestPrice) / $product->referencePrice;
                            }
                            return 0;
                        });
                        break;
                }
                break;
            case 'desc':
                switch ($orderByColumn) {
                    case 'category_name':
                        $products = $products->sortByDesc(function ($product) {
                            return $product->category->category_name;
                        });
                        break;
                    case 'product_name':
                        $products = $products->sortByDesc(function ($product) {
                            return $product->product_name;
                        });
                        break;
                    case 'ref_price':
                        $products = $products->sortByDesc(function ($product) {
                            return $product->referencePrice;
                        });
                        break;
                    case 'cheapest':
                        $products = $products->sortByDesc(function ($product) {
                            return $product->referencePrice == $product->cheapestPrice;
                        });
                        break;
                    case 'cheapest_price':
                        $products = $products->sortByDesc(function ($product) {
                            return $product->cheapestPrice;
                        });
                        break;
                    case 'diff_price':
                        $products = $products->sortByDesc(function ($product) {
                            if (!is_null($product->referencePrice) && !is_null($product->cheapestPrice)) {
                                return $product->referencePrice - $product->cheapestPrice;
                            }
                            return 0;
                        });
                        break;
                    case 'diff_percent':
                        $products = $products->sortByDesc(function ($product) {
                            if (!is_null($product->referencePrice) && !is_null($product->cheapestPrice)) {
                                return ($product->referencePrice - $product->cheapestPrice) / $product->referencePrice;
                            }
                            return 0;
                        });
                        break;
                }
                break;
        }

        $fileName = "export_positioning_" . Carbon::now()->format('YmdHis');
        Excel::create($fileName, function ($excel) use ($products, $position) {
            $excel->sheet('positioning view', function ($sheet) use ($products, $position) {
                $sheet->loadView('excel.positioning.index', compact(['products', 'position']));
            });
        })->download('csv');
    }
}