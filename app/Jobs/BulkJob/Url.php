<?php

namespace App\Jobs\BulkJob;

use App\Contracts\Repositories\Product\SiteContract;
use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Models\BulkJob;
use App\Models\Category;
use App\Models\Product as ProductModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Url implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $bulkJob;

    protected $urlRepo;
    protected $siteRepo;

    protected $part;

    protected $urls;

    /**
     * Create a new job instance.
     *
     * @param BulkJob $bulkJob
     * @param $part
     */
    public function __construct(BulkJob $bulkJob, $part)
    {
        $this->bulkJob = $bulkJob;
        $this->user = $bulkJob->user;
        $this->part = $part;
        $chunkSize = config('bulk_job.import.chunk_size', 500);

        $content = json_decode($bulkJob->content);
        if (!is_null($content) && json_last_error() === JSON_ERROR_NONE) {
            $content = collect($content);
            $chunks = $content->chunk($chunkSize);

            if ($chunks->count() >= $part) {
                $this->urls = $chunks->get($part - 1);
            }
        }
    }

    /**
     * Execute the job.
     *
     * @param UrlContract $urlContract
     * @param SiteContract $siteContract
     * @return void
     */
    public function handle(UrlContract $urlContract, SiteContract $siteContract)
    {
        $this->urlRepo = $urlContract;
        $this->siteRepo = $siteContract;

        $this->urls->each(function ($urlData) {
            $category_name = $urlData->category;
            $product_name = $urlData->product;
            $urlPath = $urlData->url;

            if (is_null($category_name) || is_null($product_name) || is_null($urlPath)) {
                return false;
            }

            /*category validation*/
            $category = $this->user->categories()->where('category_name', $category_name)->first();
            if (is_null($category)) {
                $category = $this->user->categories()->save(new Category([
                    'category_name' => $category_name
                ]));
            }

            /*product validation*/
            $product = $category->products()->where('product_name', $product_name)->first();
            if (is_null($product)) {
                $product = $this->user->products()->save(new ProductModel([
                    'product_name' => $product_name
                ]));
                $category->products()->save($product);
            }
            $url = $this->urlRepo->getByFullPathOrCreate([
                'full_path' => $urlPath
            ]);

            $siteCount = $url->sites()->where('product_id', $product->getKey())->count();
            if ($siteCount == 0) {
                $site = $this->siteRepo->store([]);
                $product->sites()->save($site);
                $url->sites()->save($site);
                if ($url->items()->count() == 1) {
                    $item = $url->items()->first();
                    $item->sites()->save($site);
                }
            }

            return $urlData;
        });

        $this->bulkJob->increment('completed');

        if ($this->bulkJob->completed == $this->bulkJob->chunks) {
            $this->bulkJob->statusNull();
            $this->bulkJob->archive();
        }
    }
}
