<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use App\Repositories\Contracts\FinanceLogInterface;
use App\Repositories\Eloquent\FinanceLogReposatory;
use App\Repositories\Contracts\VRCEventInterface;
use App\Repositories\Eloquent\VRCEventReposatory;
use App\Repositories\Contracts\ViewBeneficiaryProfileInterface;
use App\Repositories\Eloquent\ViewBeneficiaryProfileRepository;
use App\Repositories\Contracts\VRCFlowInterface;
use App\Repositories\Eloquent\VRCFlowRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FinanceLogInterface::class, FinanceLogReposatory::class);
        $this->app->bind(VRCEventInterface::class, VRCEventReposatory::class);
        $this->app->bind(ViewBeneficiaryProfileInterface::class, ViewBeneficiaryProfileRepository::class);
        $this->app->bind(VRCFlowInterface::class, VRCFlowRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
