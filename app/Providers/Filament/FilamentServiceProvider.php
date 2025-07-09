<?php

namespace App\Providers;

use App\Filament\Resources\LogResource;
use Filament\Facades\Filament;
use Filament\FilamentServiceProvider as BaseProvider;

class FilamentServiceProvider extends BaseProvider
{
    public function boot()
    {
        parent::boot();

        // تسجيل الموارد
        Filament::registerResources([
            LogResource::class,
        ]);
    }
}
