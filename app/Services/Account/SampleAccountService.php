<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 30/05/2017
 * Time: 9:43 AM
 */

namespace App\Services\Account;


use App\Contracts\Repositories\Product\CategoryContract;
use App\Contracts\Repositories\UserManagement\UserContract;
use App\Validators\Account\SampleAccount\StoreValidator;

class SampleAccountService
{
    #region repositories

    protected $userRepo;

    protected $categoryRepo;

    #endregion

    #region validators

    protected $storeValidator;

    #endregion

    public function __construct(UserContract $userContract, CategoryContract $categoryContract,
                                StoreValidator $storeValidator)
    {
        #region repositories binding
        $this->userRepo = $userContract;
        $this->categoryRepo = $categoryContract;
        #endregion

        #region validators binding
        $this->storeValidator = $storeValidator;
        #endregion
    }

    public function load(array $data = [])
    {
        $user = auth()->user();

        $subscription = $user->subscription;
        $location = 'au';
        if (!is_null($subscription)) {
            $location = $subscription->location;
        }
        switch ($location) {
            case 'us':
                $user_id = config('sample_account.us');
                break;
            case 'au':
            default:
                $user_id = config('sample_account.au');
        }
        $sampleUser = $this->userRepo->get($user_id);
        $categories = $sampleUser->categories;
        return $categories;
    }

    public function store(array $data = [])
    {
        $this->storeValidator->validate($data);

        $user = auth()->user();

        $user->update($data);

        if (array_has($data, 'selected_category_ids') && !empty(array_get($data, 'selected_category_ids'))) {
            $selectedCategoryIds = array_get($data, 'selected_category_ids');
            foreach ($selectedCategoryIds as $selectedCategoryId) {
                $sampleCategory = $this->categoryRepo->get($selectedCategoryId);
                $userCategory = $user->categories()->where('category_name', $sampleCategory->category_name)->first();
                if (is_null($userCategory)) {
                    $userCategory = $user->categories()->save($sampleCategory->replicate());
                }
                $sampleProducts = $sampleCategory->products;
                foreach ($sampleProducts as $sampleProduct) {
                    $userProduct = $userCategory->products()->where('product_name', $sampleProduct->product_name)->first();
                    if (is_null($userProduct)) {
                        $userProduct = $sampleProduct->replicate();
                        $userProduct = $userCategory->products()->save($userProduct);
                        $userProduct = $user->products()->save($userProduct);

                    }
                    $sampleSites = $sampleProduct->sites;
                    foreach ($sampleSites as $sampleSite) {
                        $userSite = $userProduct->sites()->where('url_id', $sampleSite->url_id)->where('item_id', $sampleSite->item_id)->first();
                        if (is_null($userSite)) {
                            $userSite = $userProduct->sites()->save($sampleSite->replicate());
                        }
                    }
                }
            }
        }


        $this->userRepo->updateMetas($user, $data);


        $user->set_samples = 'y';
        $user->save();
    }
}