<?php

namespace App\Jobs;

use App\Models\Item;
use App\Models\Store;
use App\Services\Importer\Importer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class UpdateStock
 * @package App\Jobs
 */
class UpdateStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Item[]
     */
    protected $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Execute the job.
     *
     * @param \App\Services\Importer\Importer $importer
     *
     * @return void
     */
    public function handle(Importer $importer)
    {
        Store::all()->each(function (Store $store) use ($importer) {
            /** @var Item $item */
            foreach ($this->items as $item) {
                $importer->item($store, $item);
            }
        });
    }
}
