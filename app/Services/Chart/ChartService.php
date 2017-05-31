<?php

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 31/05/2017
 * Time: 10:29 AM
 */

namespace App\Services\Chart;

use App\Contracts\Repositories\Product\CategoryContract;
use App\Contracts\Repositories\Product\ProductContract;
use App\Contracts\Repositories\Product\SiteContract;
use Carbon\Carbon;

class ChartService
{
    #region repositories

    protected $siteRepo;
    protected $productRepo;
    protected $categoryRepo;

    #endregion

    public function __construct(SiteContract $siteContract, ProductContract $productContract, CategoryContract $categoryContract)
    {
        #region repositories binding
        $this->siteRepo = $siteContract;
        $this->productRepo = $productContract;
        $this->categoryRepo = $categoryContract;
        #endregion
    }

    /**
     * @param array $data
     * @return null
     */
    public function sitePrices(array $data = [])
    {
        $site_id = array_get($data, 'id');
        $site = $this->siteRepo->get($site_id);
        $item = $site->item;
        if (!is_null($item)) {

            $priceItemMeta = $item->metas()->where('element', 'PRICE')->first();
            if (!is_null($priceItemMeta)) {
                $historicalPricesBuilder = $priceItemMeta->historicalPrices();
                if (array_has($data, 'from') && !is_null(array_get($data, 'from'))) {
                    $from = array_get($data, 'from');
                    $historicalPricesBuilder->where('created_at', '>=', $from);
                }
                if (array_has($data, 'to') && !is_null(array_get($data, 'to'))) {
                    $to = array_get($data, 'to');
                    $historicalPricesBuilder->where('created_at', '<=', $to);
                }
                $historicalPrices = $historicalPricesBuilder->get();

                $resolution = array_get($data, 'resolution', 'day');

                $output = collect();

                switch ($resolution) {
                    case 'day':
                        $groupedPrices = $historicalPrices->groupBy(function ($price, $index) {
                            return Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at)->format('Y-m-d');
                        });
                        foreach ($groupedPrices as $groupedPrice) {
                            $average = $groupedPrice->avg('amount');
                            $average = round($average, 2);
                            $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $groupedPrice->first()->created_at)->hour(0)->minute(0)->second(0);

                            $output->push([
                                $createdAt->timestamp * 1000,
                                $average,
                            ]);
                        }
                        break;
                    case 'week':
                        $groupedPrices = $historicalPrices->groupBy(function ($price, $index) {
                            return Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at)->format('Y-W');
                        });

                        foreach ($groupedPrices as $groupedPrice) {
                            $average = $groupedPrice->avg('amount');
                            $average = round($average, 2);
                            $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $groupedPrice->first()->created_at)->startOfWeek()->hour(0)->minute(0)->second(0);
                            $output->push([
                                $createdAt->timestamp * 1000,
                                $average,
                            ]);
                        }
                        break;
                    case 'month':
                        $groupedPrices = $historicalPrices->groupBy(function ($price, $index) {
                            return Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at)->format('Y-m');
                        });

                        foreach ($groupedPrices as $groupedPrice) {
                            $average = $groupedPrice->avg('amount');
                            $average = round($average, 2);
                            $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $groupedPrice->first()->created_at)->startOfMonth()->hour(0)->minute(0)->second(0);
                            $output->push([
                                $createdAt->timestamp * 1000,
                                $average,
                            ]);
                        }
                        break;
                }
                return $output;
            }
        }
        return null;
    }

    /**
     * @param array $data
     * @return array|\Illuminate\Support\Collection
     */
    public function productPrices(array $data = [])
    {
        $product_id = array_get($data, 'id');
        $product = $this->productRepo->get($product_id);

        $sites = $product->sites;

        $finalOutput = collect();

        foreach ($sites as $site) {
            $item = $site->item;
            if (!is_null($item)) {
                $priceItemMeta = $item->metas()->where('element', 'PRICE')->first();
                if (!is_null($priceItemMeta)) {
                    $historicalPricesBuilder = $priceItemMeta->historicalPrices();
                    if (array_has($data, 'from') && !is_null(array_get($data, 'from'))) {
                        $from = array_get($data, 'from');
                        $historicalPricesBuilder->where('created_at', '>=', $from);
                    }
                    if (array_has($data, 'to') && !is_null(array_get($data, 'to'))) {
                        $to = array_get($data, 'to');
                        $historicalPricesBuilder->where('created_at', '<=', $to);
                    }
                    $historicalPrices = $historicalPricesBuilder->get();

                    $resolution = array_get($data, 'resolution', 'day');

                    $output = collect();

                    switch ($resolution) {
                        case 'day':
                            $groupedPrices = $historicalPrices->groupBy(function ($price, $index) {
                                $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at);
                                return $createdAt->format('Y-m-d');
                            });
                            foreach ($groupedPrices as $groupedPrice) {
                                $average = $groupedPrice->avg('amount');
                                $average = round($average, 2);
                                $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $groupedPrice->first()->created_at)->hour(0)->minute(0)->second(0);

                                $output->push([
                                    $createdAt->timestamp * 1000,
                                    $average,
                                ]);
                            }
                            break;
                        case 'week':
                            $groupedPrices = $historicalPrices->groupBy(function ($price, $index) {
                                $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at);
                                return $createdAt->format('Y-W');
                            });

                            foreach ($groupedPrices as $groupedPrice) {
                                $average = $groupedPrice->avg('amount');
                                $average = round($average, 2);
                                $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $groupedPrice->first()->created_at)->startOfWeek()->hour(0)->minute(0)->second(0);
                                $output->push([
                                    $createdAt->timestamp * 1000,
                                    $average,
                                ]);
                            }
                            break;
                        case 'month':
                            $groupedPrices = $historicalPrices->groupBy(function ($price, $index) {
                                return Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at)->format('Y-m');
                            });

                            foreach ($groupedPrices as $groupedPrice) {
                                $average = $groupedPrice->avg('amount');
                                $average = round($average, 2);
                                $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $groupedPrice->first()->created_at)->startOfMonth()->hour(0)->minute(0)->second(0);
                                $output->push([
                                    $createdAt->timestamp * 1000,
                                    $average,
                                ]);
                            }
                            break;
                    }

                    $finalOutput->push([
                        'site' => $site->url->domainFullPath,
                        'data' => $output,
                    ]);
                }
            }
        }

        return $finalOutput;
    }

    /**
     * @param array $data
     */
    public function categoryPrices(array $data = [])
    {
        $category_id = array_get($data, 'id');
        $category = $this->categoryRepo->get($category_id);

        $products = $category->products;

        $finalOutput = collect();

        foreach ($products as $product) {

            $sites = $product->sites;

            $sitesOutput = collect();

            foreach ($sites as $site) {

                $item = $site->item;
                if (!is_null($item)) {
                    $priceItemMeta = $item->metas()->where('element', 'PRICE')->first();
                    if (!is_null($priceItemMeta)) {
                        $historicalPricesBuilder = $priceItemMeta->historicalPrices();
                        if (array_has($data, 'from') && !is_null(array_get($data, 'from'))) {
                            $from = array_get($data, 'from');
                            $historicalPricesBuilder->where('created_at', '>=', $from);
                        }
                        if (array_has($data, 'to') && !is_null(array_get($data, 'to'))) {
                            $to = array_get($data, 'to');
                            $historicalPricesBuilder->where('created_at', '<=', $to);
                        }
                        $historicalPrices = $historicalPricesBuilder->get();

                        $resolution = array_get($data, 'resolution', 'day');

                        switch ($resolution) {
                            case 'day':
                                $groupedPrices = $historicalPrices->groupBy(function ($price, $index) {
                                    $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at);
                                    return $createdAt->format('Y-m-d');
                                });
                                foreach ($groupedPrices as $groupedPrice) {
                                    $average = $groupedPrice->avg('amount');
                                    $average = round($average, 2);
                                    $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $groupedPrice->first()->created_at)->hour(0)->minute(0)->second(0);

                                    $sitesOutput->push([
                                        'created_at' => $createdAt->format('Y-m-d'),
                                        'average' => $average,
                                    ]);
                                }
                                break;
                            case 'week':
                                $groupedPrices = $historicalPrices->groupBy(function ($price, $index) {
                                    $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at);
                                    return $createdAt->format('Y-W');
                                });

                                foreach ($groupedPrices as $groupedPrice) {
                                    $average = $groupedPrice->avg('amount');
                                    $average = round($average, 2);
                                    $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $groupedPrice->first()->created_at)->startOfWeek()->hour(0)->minute(0)->second(0);
                                    $sitesOutput->push([
                                        'created_at' => $createdAt->format('Y-W'),
                                        'average' => $average,
                                    ]);
                                }
                                break;
                            case 'month':
                                $groupedPrices = $historicalPrices->groupBy(function ($price, $index) {
                                    return Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at)->format('Y-m');
                                });

                                foreach ($groupedPrices as $groupedPrice) {
                                    $average = $groupedPrice->avg('amount');
                                    $average = round($average, 2);
                                    $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $groupedPrice->first()->created_at)->startOfMonth()->hour(0)->minute(0)->second(0);
                                    $sitesOutput->push([
                                        'created_at' => $createdAt->format('Y-m'),
                                        'average' => $average,
                                    ]);
                                }
                                break;
                        }
                    }
                }
            }
            $groupedSiteOutput = $sitesOutput->groupBy(function ($siteOutput) {
                return array_get($siteOutput, 'created_at');
            });

            $averageOutput = collect();
            $rangeOutput = collect();
            foreach ($groupedSiteOutput as $date => $siteAverage) {
                $averageOutput->push([
                    Carbon::createFromFormat('Y-m-d', $date)->hour(0)->minute(0)->second(0)->timestamp * 1000,
                    round($siteAverage->avg('average'), 2)
                ]);

                $rangeOutput->push([
                    Carbon::createFromFormat('Y-m-d', $date)->hour(0)->minute(0)->second(0)->timestamp * 1000,
                    round($siteAverage->min('average'), 2),
                    round($siteAverage->max('average'), 2),
                ]);
            }


            $finalOutput->push([
                'name' => $product->product_name,
                'average' => $averageOutput,
                'range' => $rangeOutput,
            ]);
        }

        return $finalOutput;
    }
}