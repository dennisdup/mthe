<?php

namespace App\Providers;

use App\Models\Item;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Relation::morphMap([
            Item::MORPH_TYPE => Item::class,
            Variant::MORPH_TYPE => Variant::class,
        ]);
    }
}
