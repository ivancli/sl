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

        $priceMeta->confs()->save(new DomainMetaConf([
            'element' => 'PARSER_CLASS',
            'value' => 'XPathParser',
            'order' => 0
        ]));

        unset($domain);
        unset($priceMeta);

        #endregion


        #region eBay

        $domain = $this->domainRepo->store([
            'name' => 'eBay',
            'full_path' => 'http://www.ebay.com',
        ]);

        $domain->confs()->save(new DomainConf([
            'element' => 'CUSTOM_ITEM_GENERATOR',
            'value' => 'EBAY\MultipleItemGenerator'
        ]));

        $domain->confs()->save(new DomainConf([
            'element' => 'CUSTOM_CRAWLER',
            'value' => 'EBAY\APICrawler'
        ]));

        $domain->confs()->save(new DomainConf([
            'element' => 'CUSTOM_PARSER',
            'value' => 'EBAY\MultipleItemParser'
        ]));

        $priceMeta = $domain->metas()->save(new DomainMeta([
            'element' => 'PRICE',
            'format_type' => 'decimal',
            'historical_type' => 'price',
            'multi' => 'n'
        ]));

        $priceMeta->confs()->save(new DomainMetaConf([
            'element' => 'ARRAY',
            'value' => 'price.value',
            'order' => 0
        ]));

        $priceMeta->confs()->save(new DomainMetaConf([
            'element' => 'ARRAY',
            'value' => 'currentBidPrice.value',
            'order' => 1
        ]));

        $priceMeta = $domain->metas()->save(new DomainMeta([
            'element' => 'SELLER_USERNAME',
            'multi' => 'n'
        ]));

        $priceMeta->confs()->save(new DomainMetaConf([
            'element' => 'ARRAY',
            'value' => 'seller.username',
            'order' => 0
        ]));

        unset($domain);
        unset($priceMeta);
        #endregion

        #region ebay au


        $domain = $this->domainRepo->store([
            'name' => 'eBay',
            'full_path' => 'http://www.ebay.com.au',
        ]);

        $domain->confs()->save(new DomainConf([
            'element' => 'CUSTOM_ITEM_GENERATOR',
            'value' => 'EBAY\MultipleItemGenerator'
        ]));

        $domain->confs()->save(new DomainConf([
            'element' => 'CUSTOM_CRAWLER',
            'value' => 'EBAY\APICrawler'
        ]));

        $domain->confs()->save(new DomainConf([
            'element' => 'CUSTOM_PARSER',
            'value' => 'EBAY\MultipleItemParser'
        ]));

        $priceMeta = $domain->metas()->save(new DomainMeta([
            'element' => 'PRICE',
            'format_type' => 'decimal',
            'historical_type' => 'price',
            'multi' => 'n'
        ]));

        $priceMeta->confs()->save(new DomainMetaConf([
            'element' => 'ARRAY',
            'value' => 'price.value',
            'order' => 0
        ]));

        $priceMeta->confs()->save(new DomainMetaConf([
            'element' => 'ARRAY',
            'value' => 'currentBidPrice.value',
            'order' => 1
        ]));

        $priceMeta = $domain->metas()->save(new DomainMeta([
            'element' => 'SELLER_USERNAME',
            'multi' => 'n'
        ]));

        $priceMeta->confs()->save(new DomainMetaConf([
            'element' => 'ARRAY',
            'value' => 'seller.username',
            'order' => 0
        ]));

        unset($domain);
        unset($priceMeta);
        #endregion
    }
}