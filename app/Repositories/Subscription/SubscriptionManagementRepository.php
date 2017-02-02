<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 1:37 PM
 */

namespace App\Repositories\Subscription;


use App\Contracts\Repositories\Subscription\ProductContract;
use App\Contracts\Repositories\Subscription\ProductFamilyContract;
use App\Contracts\Repositories\Subscription\SubscriptionContract;
use App\Contracts\Repositories\Subscription\SubscriptionManagementContract;

class SubscriptionManagementRepository implements SubscriptionManagementContract
{
    var $productFamilyRepo;
    var $productRepo;
    var $subscriptionRepo;

    public function __construct(ProductFamilyContract $productFamilyContract, ProductContract $productContract, SubscriptionContract $subscriptionContract)
    {
        $this->productFamilyRepo = $productFamilyContract;
        $this->productRepo = $productContract;
        $this->subscriptionRepo = $subscriptionContract;
    }

    /**
     * Retrieve data for pricing tables
     *
     * @return mixed
     */
    public function getPricingTables()
    {
        $families = $this->productFamilyRepo->getProductFamilies();
        $outputProductFamilies = [];
        foreach ($families as $familyIndex => $family) {
            $products = $this->productRepo->getProductsByProductFamilyID($family->id);
            if (isset($products->errors) || count($products) == 0) {
                continue;
            }

            foreach ($products as $product) {
                if (strpos($product->handle, 'onboarding') === false) {
                    $finalProduct = $product;
                }
            }

            if (!isset($finalProduct)) {
                continue;
            }

            $subscriptionPreview = $this->subscriptionRepo->previewSubscription([
                "product_id" => $finalProduct->id,
                "customer_attributes" => array(
                    "first_name" => "Spot",
                    "last_name" => "Lite",
                    "email" => "admin@spotlite.com.au",
                    "country" => "AU",
                    "state" => "New South Wales"
                ),
                "payment_profile_attributes" => array(
                    "billing_country" => "AU",
                    "billing_state" => "NSW",
                )
            ]);

            $finalProduct->criteria = json_decode($finalProduct->description);
            $finalProductFamily = $family;
            $finalProductFamily->product = $finalProduct;
            $finalProductFamily->preview = $subscriptionPreview;
            $outputProductFamilies[] = $finalProductFamily;

            unset($finalProduct);
            unset($products);
            unset($finalProductFamily);
        }

        $outputProductFamilies = collect($outputProductFamilies);
        $outputProductFamilies = $outputProductFamilies->sortBy('product.price_in_cents')->values();
        return $outputProductFamilies;
    }
}