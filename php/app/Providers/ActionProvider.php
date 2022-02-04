<?php

namespace App\Providers;

use App\Models\Stock;
use App\Services\Actions\CreateOrder;
use App\Services\Actions\GetStock;
use App\Services\Actions\UpdateStatus;
use App\Services\Export\DTO\StockDataTransferObject;
use Illuminate\Support\ServiceProvider;

/**
 * Class ActionProvider
 * @package App\Providers
 */
class ActionProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(GetStock::class, function ($app) {
            $dto = new StockDataTransferObject(config('munthe2.actions.getstock.mapping'), Stock::class);

            return new GetStock(
                config('munthe2.actions.getstock.wsdl'),
                config('munthe2.credentials.user'),
                config('munthe2.credentials.password'),
                $dto
            );
        });

        $this->app->bind(CreateOrder::class, function() {
            return new CreateOrder(
                config('munthe2.actions.createorder.wsdl'),
                config('munthe2.credentials.user'),
                config('munthe2.credentials.password')
            );
        });

        $this->app->bind(UpdateStatus::class, function() {
            return new UpdateStatus(
                config('munthe2.actions.updatestatus.wsdl'),
                config('munthe2.credentials.user'),
                config('munthe2.credentials.password')
            );
        });
    }
}
