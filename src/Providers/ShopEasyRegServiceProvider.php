<?php

namespace Azuriom\Plugin\ShopEasyReg\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;

class ShopEasyRegServiceProvider extends BasePluginServiceProvider
{
    /**
     * Глобальные HTTP middleware плагина.
     */
    protected array $middleware = [
        // \Azuriom\Plugin\ShopEasyReg\Middleware\ExampleMiddleware::class,
    ];

    /**
     * Группы middleware для маршрутов плагина.
     */
    protected array $middlewareGroups = [];

    /**
     * Middleware для маршрутов плагина.
     */
    protected array $routeMiddleware = [
        // 'example' => \Azuriom\Plugin\ShopEasyReg\Middleware\ExampleRouteMiddleware::class,
    ];

    /**
     * Связка политик с моделями плагина.
     *
     * @var array<string, string>
     */
    protected array $policies = [
        // User::class => UserPolicy::class,
    ];

    /**
     * Регистрация сервисов плагина.
     */
    public function register(): void
    {
        // $this->registerMiddleware();

        //
    }

    /**
     * Загрузка сервисов плагина.
     */
    public function boot(): void
    {
        // $this->registerPolicies();

        $this->loadViews();

        $this->loadTranslations();

        $this->loadMigrations();

        $this->registerRouteDescriptions();

        $this->registerAdminNavigation();

        $this->registerUserNavigation();

        // Включаем гостевую корзину по умолчанию при первой установке
        if (setting('shop.cart_auth') === null) {
            \Azuriom\Models\Setting::updateSettings([
                'shop.cart_auth' => true,
            ]);
        }

        // Переопределяем шаблоны магазина нашими версиями.
        // Непереопределённые представления будут загружены из оригинального плагина.
        view()->prependNamespace('shop', $this->pluginResourcePath('views/overrides'));

        // Маршруты магазина переопределяются в файле routes/web.php
    }


    /**
     * Маршруты, которые можно добавить в навигацию.
     *
     * @return array<string, string>
     */
    protected function routeDescriptions(): array
    {
        return [
            'shopeasyreg.admin.index' => trans('shopeasyreg::messages.nav.title'),
        ];
    }

    /**
     * Разделы панели администратора.
     *
     * @return array<string, array<string, string>>
     */
    protected function adminNavigation(): array
    {
        return [
            'shopeasyreg' => [
                'name' => trans('shopeasyreg::messages.nav.title'),
                'icon' => 'bi bi-person-plus',
                'route' => 'shopeasyreg.admin.index',
            ],
        ];
    }

    /**
     * Ссылки в пользовательском меню.
     *
     * @return array<string, array<string, string>>
     */
    protected function userNavigation(): array
    {
        return [];
    }
}
