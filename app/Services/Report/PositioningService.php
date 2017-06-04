<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/06/2017
 * Time: 5:55 PM
 */

namespace App\Services\Report;


use Illuminate\Http\Request;

class PositioningService
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function load(array $data = [])
    {
        $user = auth()->user();


        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $position = array_get($data, 'position');
        $key = array_get($data, 'key');
        $category = array_get($data, 'category');
        $page = array_get($data, 'page');
        $perPage = array_get($data, 'per_page');
        $productsBuilder = $user->products();
        $productsBuilder->with('sites.item', 'category');
        $productsBuilder = $productsBuilder->orderBy($orderByColumn, $orderByDirection);
        $products = $productsBuilder->get();

        $total = $products->count();

        $products->each(function ($product) use ($data, $user) {
            $reference = array_get($data, 'reference');
            $referenceSite = null;
            $product->setAttribute('referencePrice', null);
            if (!is_null($reference)) {
                $referenceSite = $product->sites->filter(function ($site) use ($reference) {
                    if (!is_null($site->item) && !is_null($site->item->sellerUsername)) {
                        if ($site->item->sellerUsername == $reference) {
                            return true;
                        }
                    }
                    return str_contains($site->siteUrl, $reference);
                })->first();
                if (!is_null($referenceSite->item)) {
                    $product->setAttribute('referencePrice', $referenceSite->item->recentPrice);
                }
            }

            $cheapestPrice = $product->sites->min(function ($site) {
                if (!is_null($site->item) && !is_null($site->item->recentPrice)) {
                    return $site->item->recentPrice;
                }
                return true;
            });
            $secondCheapestPrice = $product->sites->filter(function ($site) use ($cheapestPrice) {
                if (!is_null($site->item) && !is_null($site->item->recentPrice)) {
                    return $site->item->recentPrice != $cheapestPrice;
                }
                return false;
            })->min(function ($site) {
                if (!is_null($site->item) && !is_null($site->item->recentPrice)) {
                    return $site->item->recentPrice;
                }
                return true;
            });

            $mostExpensivePrice = $product->sites->max(function ($site) {
                if (!is_null($site->item) && !is_null($site->item->recentPrice)) {
                    return $site->item->recentPrice;
                }
                return false;
            });

            $cheapestSites = $product->sites->filter(function ($site) use ($cheapestPrice) {
                if (!is_null($site->item)) {
                    return $site->item->recentPrice == $cheapestPrice;
                }
            });

            $mostExpensiveSites = $product->sites->filter(function ($site) use ($mostExpensivePrice) {
                if (!is_null($site->item)) {
                    return $site->item->recentPrice == $mostExpensivePrice;
                }
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

        $products = $products->filter(function($product){
            return $product->sites->count() > 1;
        });

        if (!is_null($key) && !empty($key)) {

        }

        $skip = ($page - 1) * $perPage;
        if ($skip > 0) {
            $products = $products->slice($skip);
        }

        if (!is_null($length) && !empty($length)) {
            $products = $products->take($length);
        }

        $output = new \stdClass();
        $output->data = $products;
        $output->current_page = $page;
        $output->last_page = ceil($total / $perPage);
        $output->next_page = ceil($total / $perPage) > $page + 1 ? $page + 1 : null;
        $output->prev_page = $page - 1 > 0 ? $page : null;

        return $output;
    }

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
}