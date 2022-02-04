<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Store;
use App\Services\Importer\Importer;

/**
 * Class OrderCreatedListener
 *
 * @package App\Listeners
 */
class OrderCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */


    public function __construct()
    {
        //
    }

    /**
     * @param \App\Events\OrderCreated $event
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     */
    public function handle(OrderCreated $event)
    {
        $importer = new Importer();
        Store::all()->each(function (Store $store) use ($importer, $event) {
            foreach ($event->item as $eventItem) {
                $importer->item($store, $eventItem);
            }
        });
    }
}
