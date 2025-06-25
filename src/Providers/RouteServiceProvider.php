<?php

namespace Azuriom\Plugin\ShopEasyReg\Providers;

use Azuriom\Extensions\Plugin\BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * Загрузка маршрутов плагина.
     */
    public function loadRoutes(): void
    {
        // Веб-маршруты без префикса, чтобы переопределить маршруты магазина
        Route::middleware('web')
            ->group(plugin_path($this->plugin->id.'/routes/web.php'));

        Route::middleware('admin-access')
            ->prefix('admin/'.$this->plugin->id)
            ->name($this->plugin->id.'.admin.')
            ->group(plugin_path($this->plugin->id.'/routes/admin.php'));

        Route::middleware('api')
            ->prefix('api/'.$this->plugin->id)
            ->name($this->plugin->id.'.api.')
            ->group(plugin_path($this->plugin->id.'/routes/api.php'));
    }
}
