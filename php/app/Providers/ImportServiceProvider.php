<?php

namespace App\Providers;

use App\Services\Import\DTO\DataTransferObject;
use App\Services\Import\Importer;
use App\Services\Import\Product;
use App\Services\Import\Variant;
use Illuminate\Support\ServiceProvider;

/**
 * Class ImportProvider
 * @package App\Providers
 */
class ImportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(Product::class, function () {
            $dto = new DataTransferObject(config('shopify.item.mapping'));

            return new Product($dto);
        });

        $this->app->bind(Variant::class, function () {
            $dto = new DataTransferObject(config('shopify.variant.mapping'));

            return new Variant($dto);
        });

        $this->app->tag([Product::class], 'importers');

        $this->app->bind(Importer::class, function ($app) {
            return new Importer($app->tagged('importers'));
        });
    }
}
