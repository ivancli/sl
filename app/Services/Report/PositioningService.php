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
                if (!is_null($referenceSite) && !is_null($referenceSite->item)) {
                    $product->setAttribute('referencePrice', $referenceSite->item->recentPrice);
                }
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
        $productsBuilder = $productsBuilder->orderBy($orderByColumn, $orderByDirection);
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