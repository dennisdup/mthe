<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Services\Importer\Importer;
use Carbon\Carbon;
use Facades\App\Services\Actions\CreateOrder;
use Illuminate\Console\Command;
use PHPShopify\ShopifySDK;

/**
 * Class ImportCommand
 * @package App\Console\Commands
 */
class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import {store?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all all';

    /**
     * @var Importer
     */
    protected $importer;

    /**
     * ImportCommand constructor.
     * @param Importer $importer
     */
    public function __construct(Importer $importer)
    {
        $this->importer = $importer;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('store') == null) {
            foreach (Store::query()->get() as $store) {
                echo "==================[" . $store->id . "]==================";
                $this->importer->run($store);
                echo "==================[" . $store->id . "]==================";
            }
        }

        if ($storeId = $this->argument('store')) {
            $store = Store::whereId($storeId)->first();
            $this->importer->run($store);
        }
    }
}
