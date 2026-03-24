<?php

namespace App\Providers;

use App\Domains\Leads\Repositories\EloquentLeadRepository;
use App\Domains\Leads\Repositories\LeadRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LeadRepositoryInterface::class, EloquentLeadRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
