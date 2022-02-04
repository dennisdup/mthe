<?php

namespace App\Providers;

use App\Console\Commands\ExportCommand;
use App\Models\Item;
use App\Models\ItemText;
use App\Models\Price;
use App\Models\Stock;
use App\Models\Variant;
use App\Services\Export\DTO\PriceDataTransferObject;
use App\Services\Export\DTO\ProductDataTransferObject;
use App\Services\Export\DTO\StockDataTransferObject;
use App\Services\Export\DTO\TextDataTransferObject;
use App\Services\Export\DTO\VariantDataTransferObject;
use App\Services\Export\SoapParser;
use App\Services\Export\StockSoapParser;
use Illuminate\Support\ServiceProvider;

/**
 * Class ExportServiceProvider
 *
 * @package App\Providers
 */
class ExportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Services\Export\Item', function () {
            $dto = new ProductDataTransferObject(config('munthe2.export.item.mapping'), Item::class);

            return new SoapParser($dto, config('munthe2.export.item.wsdl'), config('munthe2.export.item.function'));
        });

        $this->app->bind('App\Services\Export\Variant', function () {
            $dto = new VariantDataTransferObject(config('munthe2.export.variant.mapping'), Variant::class);

            return new SoapParser($dto, config('munthe2.export.variant.wsdl'), config('munthe2.export.variant.function'));
        });

        $this->app->bind('App\Services\Export\Price', function () {
            $dto = new PriceDataTransferObject(config('munthe2.export.price.mapping'), Price::class);

            return new SoapParser($dto, config('munthe2.export.price.wsdl'), config('munthe2.export.price.function'));
        });

        $this->app->bind('App\Services\Export\Stock', function () {
            $dto = new StockDataTransferObject(config('munthe2.actions.getstock.mapping'), Stock::class);

            return new StockSoapParser($dto, '', config('munthe2.export.price.function'));
        });

        $this->app->bind('App\Services\Export\Text', function () {
            $dto = new TextDataTransferObject(config('munthe2.export.text.mapping'), ItemText::class);

            return new SoapParser($dto, config('munthe2.export.text.wsdl'), config('munthe2.export.text.function'));
        });

        $this->app->tag([
            'App\Services\Export\Item',
            'App\Services\Export\Variant',
            'App\Services\Export\Price',
            'App\Services\Export\Text',
        ], 'parsers');

        $this->app->bind(ExportCommand::class, function () {
            return new ExportCommand($this->app->tagged('parsers'));
        });
    }
}
