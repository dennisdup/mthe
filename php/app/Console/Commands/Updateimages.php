<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Services\Actions\UpdateImagesShopify;
use Illuminate\Console\Command;

/**
 * Class Updateimages
 *
 * @package App\Console\Commands
 */
class Updateimages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Images for All Shopify shops';

    protected $imgService;

    /**
     * Create a new command instance.
     *
     * @param \App\Services\Actions\UpdateImagesShopify $imgService
     */
    public function __construct(UpdateImagesShopify $imgService)
    {
        parent::__construct();
        $this->imgService = $imgService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (Store::query()->get() as $store) {
            $this->imgService->update($store);
        }
    }
}
