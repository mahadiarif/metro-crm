<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tyro-dashboard.index');
});
Route::middleware(['web', 'auth', 'tyro.role.protection'])->prefix('dashboard')->name('tyro-dashboard.')->group(function () {
    Route::get('/reports/sales', [\App\Http\Controllers\Dashboard\ReportController::class , 'sales'])->name('reports.sales');
    Route::get('/reports/visits', [\App\Http\Controllers\Dashboard\ReportController::class , 'visits'])->name('reports.visits');
    Route::get('/reports/leads', [\App\Http\Controllers\Dashboard\ReportController::class , 'leads'])->name('reports.leads');

    // Override User Management to enforce RBAC logic
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::post('users/{user}/suspend', [\App\Http\Controllers\UserController::class, 'suspend'])->name('users.suspend');
    Route::post('users/{user}/unsuspend', [\App\Http\Controllers\UserController::class, 'unsuspend'])->name('users.unsuspend');
    Route::post('users/{user}/reset-2fa', [\App\Http\Controllers\UserController::class, 'reset2fa'])->name('users.reset-2fa');
    Route::post('users/{user}/login-as', [\App\Http\Controllers\UserController::class, 'loginAs'])->name('users.login-as');

    Route::post('/notes', [\App\Http\Controllers\Dashboard\NoteController::class , 'store'])->name('notes.store');
    Route::delete('/notes/{note}', [\App\Http\Controllers\Dashboard\NoteController::class , 'destroy'])->name('notes.destroy');

    // Pipelines
    Route::get('/pipelines/kanban', [App\Http\Controllers\Dashboard\PipelineController::class , 'kanban'])->name('pipelines.kanban');
    Route::post('/pipelines/update-stage', [App\Http\Controllers\Dashboard\PipelineController::class , 'updateStage'])->name('pipelines.update-stage');

    // Analytics
    Route::get('/analytics/data', [App\Http\Controllers\Dashboard\AnalyticsController::class , 'dashboard'])->name('analytics.data');

    // Performance Reports
    Route::get('/reports/user/{user}', [\App\Http\Controllers\Dashboard\ReportController::class , 'userPerformance'])->name('reports.user-performance');
    Route::get('/reports/team-performance', [\App\Http\Controllers\Dashboard\ReportController::class , 'teamPerformance'])->name('reports.team-performance');

    // Proposals
    Route::post('/proposals', [\App\Http\Controllers\Dashboard\ProposalController::class, 'store'])->name('proposals.store');
    Route::post('/proposals/{proposal}/send', [\App\Http\Controllers\Dashboard\ProposalController::class, 'send'])->name('proposals.send');
    Route::put('/proposals/{proposal}/status', [\App\Http\Controllers\Dashboard\ProposalController::class, 'updateStatus'])->name('proposals.status');

    // Quarterly Reports
    Route::get('/reports/quarterly', [\App\Http\Controllers\Dashboard\ReportController::class, 'quarterly'])->name('reports.quarterly');
    Route::get('/reports/quarterly/export', [\App\Http\Controllers\Dashboard\ReportController::class, 'exportQuarterly'])->name('reports.export.quarterly');

    // Campaign Reports
    Route::get('/reports/campaigns/export', [\App\Http\Controllers\Dashboard\ReportController::class, 'exportCampaigns'])->name('reports.export.campaigns');

    // Marketing Module
    Route::get('/marketing/campaigns', [\App\Http\Controllers\Dashboard\MarketingController::class, 'index'])->name('marketing.campaigns.index');
    Route::get('/marketing/campaigns/create', [\App\Http\Controllers\Dashboard\MarketingController::class, 'create'])->name('marketing.campaigns.create');
    Route::get('/marketing/campaigns/{campaign}', [\App\Http\Controllers\Dashboard\MarketingController::class, 'show'])->name('marketing.campaigns.show');
    Route::get('/marketing/templates', [\App\Http\Controllers\Dashboard\MarketingController::class, 'templates'])->name('marketing.templates.index');
    Route::get('/marketing/configuration', [\App\Http\Controllers\Dashboard\MarketingController::class, 'settings'])->name('marketing.configuration');
    Route::get('/leads/{lead}/visit-history', [\App\Http\Controllers\Dashboard\VisitHistoryController::class, 'getHistory'])->name('leads.visit-history');
    Route::get('/services/{service}/packages', [\App\Http\Controllers\Dashboard\ProductLookupController::class, 'getPackages'])->name('services.packages');
});
