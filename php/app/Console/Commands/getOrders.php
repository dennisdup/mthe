<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Services\OrderService;
use Facades\App\Services\Actions\CreateOrder;
use Illuminate\Console\Command;
use PHPShopify\ShopifySDK;

/**
 * Class getOrders
 *
 * @package App\Console\Commands
 */
class getOrders extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var \App\Services\OrderService
     */
    protected $orderService;

    /**
     * Create a new command instance.
     *
     * @param \App\Services\OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    /**
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     */
    public function handle()
    {
        foreach (Store::query()->get() as $store) {

            $sdk = new ShopifySDK([
                'ApiUrl'   => $store->shop_name,
                'ApiKey'   => $store->api_key,
                'Password' => $store->api_password,
            ]);

            foreach ($sdk->Order->get() as $item) {
                CreateOrder::createOrder($store, $item, $this->orderService);
            }
        }
    }
}
