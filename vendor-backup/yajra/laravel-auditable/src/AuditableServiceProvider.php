<?php

namespace Yajra\Auditable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class AuditableServiceProvider extends ServiceProvider
{
    /**
     * Boot the package.
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/auditable.php', 'auditable');
        $this->publishes([
            __DIR__ . '/config/auditable.php' => base_path('config/auditable.php'),
        ], 'auditable');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Blueprint::macro('auditable', function () {
            $this->uuid('created_by')->nullable()->index();
            $this->uuid('updated_by')->nullable()->index();
        });

        Blueprint::macro('dropAuditable', function () {
            $this->dropColumn(['created_by', 'updated_by']);
        });

        Blueprint::macro('auditableWithDeletes', function () {
            $this->uuid('created_by')->nullable()->index();
            $this->uuid('updated_by')->nullable()->index();
            $this->uuid('deleted_by')->nullable()->index();
        });

        Blueprint::macro('dropAuditableWithDeletes', function () {
            $this->dropColumn(['created_by', 'updated_by', 'deleted_by']);
        });
    }
}
