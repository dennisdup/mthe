<?php

namespace App\Console\Commands;

use App\Services\UpdateStockService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class updateStock
 *
 * @package App\Console\Commands
 */
class updateStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var
     */
    protected $stockService;

    /**
     * Create a new command instance.
     *
     * @param \App\Services\UpdateStockService $service
     */
    public function __construct(UpdateStockService $service)
    {
        parent::__construct();
        $this->stockService = $service;
    }

    /**
     * @throws \SoapFault
     */
    public function handle()
    {
        $this->stockService->getStockFromErp();
    }
}
