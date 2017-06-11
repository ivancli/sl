<?php

namespace App\Jobs\BulkJob;

use App\Models\BulkJob;
use App\Models\Category;
use App\Models\Product as ProductModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Product implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $bulkJob;

    protected $part;

    protected $products;

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
                $this->products = $chunks->get($part - 1);
            }
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->products->each(function ($productData) {
            $category_name = $productData->category;
            $product_name = $productData->product;
            $sku = isset($productData->sku) ? $productData->sku : null;
            $supplier = isset($productData->supplier) ? $productData->supplier : null;
            $brand = isset($productData->brand) ? $productData->brand : null;
            $cost_price = isset($productData->cost_price) ? $productData->cost_price : null;

            if (is_null($category_name) || is_null($product_name)) {
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

            $meta = $product->meta;
            if (!is_null($brand)) {
                $meta->brand = $brand;
            }
            if (!is_null($supplier)) {
                $meta->supplier = $supplier;
            }
            if (!is_null($sku)) {
                $meta->sku = $sku;
            }
            if (!is_null($cost_price)) {
                $meta->cost_price = $cost_price;
            }
            $meta->save();
            return $productData;
        });

        $this->bulkJob->increment('completed');

        if ($this->bulkJob->completed == $this->bulkJob->chunks) {
            $this->bulkJob->statusNull();
            $this->bulkJob->archive();
        }
    }
}
