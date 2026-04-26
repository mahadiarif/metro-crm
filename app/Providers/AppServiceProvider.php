<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Livewire\Livewire::component('dashboard.quarterly-sales-report', \App\Http\Livewire\Dashboard\QuarterlySalesReport::class);
        \Livewire\Livewire::component('dashboard.visit-form', \App\Http\Livewire\Dashboard\VisitForm::class);

        \Livewire\Livewire::component('marketing.campaign-index', \App\Domains\Marketing\Livewire\CampaignIndex::class);
        \Livewire\Livewire::component('marketing.campaign-create', \App\Domains\Marketing\Livewire\CampaignCreate::class);
        \Livewire\Livewire::component('marketing.campaign-show', \App\Domains\Marketing\Livewire\CampaignShow::class);
        \Livewire\Livewire::component('marketing.marketing-settings', \App\Domains\Marketing\Livewire\MarketingSettings::class);
        \Livewire\Livewire::component('marketing.template-manager', \App\Domains\Marketing\Livewire\TemplateManager::class);

        \Illuminate\Support\Facades\View::composer(
            ['tyro-dashboard::dashboard.admin', 'tyro-dashboard::dashboard.user'],
            \App\View\Composers\CRMStatsComposer::class
        );

        \Illuminate\Support\Facades\View::composer(
            'tyro-dashboard::*',
            function ($view) {
                $view->with('branding', config('tyro-dashboard.branding', []));
            }
        );

        \Illuminate\Support\Facades\View::composer(
            ['tyro-dashboard::resources.create', 'tyro-dashboard::resources.edit'],
            \App\View\Composers\LeadFormComposer::class
        );

        \App\Models\Visit::observe(\App\Observers\VisitObserver::class);
        \App\Models\FollowUp::observe(\App\Observers\FollowUpObserver::class);
        \App\Models\Sale::observe(\App\Observers\SaleObserver::class);
        \App\Models\Note::observe(\App\Observers\NoteObserver::class);
        \App\Models\SalesCall::observe(\App\Observers\SalesCallObserver::class);
    }
}
