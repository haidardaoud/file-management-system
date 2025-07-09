<?php

namespace App\Providers;

use App\Models\Group;
use Illuminate\Support\ServiceProvider;
use App\Repositories\GroupRepository;
use App\Services\GroupService;
use App\Repositories\RequestApprovalRepository; // المستودع الجديد
use App\Services\RequestApprovalService; // الخدمة الجديدة

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // تسجيل مستودع المجموعات
        $this->app->singleton(GroupRepository::class, function ($app) {
            return new GroupRepository($app->make(Group::class));
        });

        // تسجيل خدمة المجموعات
        $this->app->singleton(GroupService::class, function ($app) {
            return new GroupService($app->make(GroupRepository::class));
        });

        // تسجيل مستودع طلبات الموافقة
        $this->app->singleton(RequestApprovalRepository::class, function ($app) {
            // في حال كان هناك حاجة لحقن نماذج أخرى
            return new RequestApprovalRepository();
        });

        // تسجيل خدمة طلبات الموافقة
        $this->app->singleton(RequestApprovalService::class, function ($app) {
            return new RequestApprovalService($app->make(RequestApprovalRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
