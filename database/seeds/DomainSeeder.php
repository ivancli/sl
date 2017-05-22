<?php
use App\Contracts\Repositories\UrlManagement\DomainContract;
use App\Models\DomainConf;
use App\Models\DomainMeta;
use App\Models\DomainMetaConf;
use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 22/05/2017
 * Time: 3:55 PM
 */
class DomainSeeder extends Seeder
{
    protected $domainRepo;

    public function __construct(DomainContract $domainContract)
    {
        $this->domainRepo = $domainContract;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #region Da Voluce Lighting

        $domain = $this->domainRepo->store([
            'name' => 'Da Voluce Lighting',
            'full_path' => 'http://www.davolucelighting.com.au',
        ]);

        $domain->confs()->save(new DomainConf([
            'element' => 'CUSTOM_ITEM_GENERATOR',
            'value' => 'DAVOLUCELIGHTING\MultipleItemGenerator'
        ]));

        $domain->confs()->save(new DomainConf([
            'element' => 'CUSTOM_PARSER',
            'value' => 'DAVOLUCELIGHTING\MultipleItemParser'
        ]));

        $priceMeta = $domain->metas()->save(new DomainMeta([
            'element' => 'PRICE',
            'format_type' => 'decimal',
            'historical_type' => 'price',
            'multi' => 'n'
        ]));

        $priceMeta->confs()->save(new DomainMetaConf([
            'element' => 'XPATH',
            'value' => '//*[@id="product_price"]',
            'order' => 0
        ]));

        unset($domain);
        unset($priceMeta);

        #endregion
    }
}