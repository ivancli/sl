<?php

use Illuminate\Database\Seeder;
use App\Contracts\Repositories\Admin\AppPrefContract;

class AppPrefSeeder extends Seeder
{
    protected $appPrefRepo;

    public function __construct(AppPrefContract $appPrefContract)
    {
        $this->appPrefRepo = $appPrefContract;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->appPrefRepo->store([
            'element' => 'CRAWL_RESERVED',
            'value' => 'n'
        ]);
        $this->appPrefRepo->store([
            'element' => 'CRAWL_LAST_RESERVED_AT',
            'value' => null
        ]);
        $this->appPrefRepo->store([
            'element' => 'SYNC_RESERVED',
            'value' => 'n'
        ]);
        $this->appPrefRepo->store([
            'element' => 'SYNC_LAST_RESERVED_AT',
            'value' => null
        ]);
        $this->appPrefRepo->store([
            'element' => 'REPORT_RESERVED',
            'value' => 'n'
        ]);
        $this->appPrefRepo->store([
            'element' => 'REPORT_LAST_RESERVED_AT',
            'value' => null
        ]);
    }
}
