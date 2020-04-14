<?php

namespace App\Providers;

use App\Models\SchemaTable;
use App\Models\SchemaTableColumn;
use App\Observers\SchemaTableColumnObserver;
use App\Observers\SchemaTableObserver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        SchemaTable::observe(SchemaTableObserver::class);
        SchemaTableColumn::observe(SchemaTableColumnObserver::class);
    }
}
