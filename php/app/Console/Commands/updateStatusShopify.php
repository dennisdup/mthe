<?php

namespace App\Console\Commands;

use App\Services\Actions\UpdateStatus;
use Illuminate\Console\Command;

/**
 * Class updateStatusShopify
 *
 * @package App\Console\Commands
 */
class updateStatusShopify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * updateStatusShopify constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service = new UpdateStatus(
            config('munthe2.actions.updatestatus.wsdl'),
            config('munthe2.credentials.user'),
            config('munthe2.credentials.password')
        );

        $service->updateByOrder();
    }
}
