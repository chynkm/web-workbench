<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        Route::bind('schema', function ($value) {
            $schema = \App\Models\Schema::select('schemas.*')
                ->join('schema_user', 'schemas.id', 'schema_id')
                ->where('user_id', Auth::id())
                ->find($value);

            return $schema ?? abort(
                redirect()->route('schemas.index')
                    ->with('alert', [
                        'class' => 'warning',
                        'message' => __('form.requested_schema_not_found'),
                    ])
            );
        });

        Route::bind('schemaTable', function ($value) {
            $schemaTable = \App\Models\SchemaTable::select('schema_tables.*')
                ->join('schema_user', 'schema_tables.schema_id', 'schema_user.schema_id')
                ->where('schema_user.user_id', Auth::id())
                ->find($value);

            return $schemaTable ?? abort(
                redirect()->route('schemas.index')
                    ->with('alert', [
                        'class' => 'warning',
                        'message' => __('form.requested_table_not_found'),
                    ])
            );
        });

        Route::bind('schemaTableColumn', function ($value) {
            $schemaTableColumn = \App\Models\SchemaTableColumn::select('schema_table_columns.*')
                ->join('schema_tables', 'schema_tables.id', 'schema_table_columns.schema_table_id')
                ->join('schema_user', 'schema_tables.schema_id', 'schema_user.schema_id')
                ->where('schema_user.user_id', Auth::id())
                ->find($value);

            return $schemaTableColumn ?? abort(
                redirect()->route('schemas.index')
                    ->with('alert', [
                        'class' => 'warning',
                        'message' => __('form.requested_table_not_found'),
                    ])
            );
        });

        Route::bind('relationship', function ($value) {
            $relationship = \App\Models\Relationship::select('relationships.*')
                ->join('schema_table_columns', 'schema_table_columns.id', 'primary_table_id')
                ->join('schema_tables', 'schema_tables.id', 'schema_table_columns.schema_table_id')
                ->join('schema_user', 'schema_tables.schema_id', 'schema_user.schema_id')
                ->where('schema_user.user_id', Auth::id())
                ->find($value);

            return $relationship ?? abort(
                redirect()->route('schemas.index')
                    ->with('alert', [
                        'class' => 'warning',
                        'message' => __('form.requested_table_not_found'),
                    ])
            );
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
