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

        // Переопределяем шаблоны магазина нашими версиями.
        // Непереопределённые представления будут загружены из оригинального плагина.
        view()->prependNamespace('shop', $this->pluginResourcePath('views/overrides'));

        // Меняем настройки маршрутов магазина для работы гостевой корзины
        $this->overrideShopRoutes();
    }

    /**
     * Удаление middleware авторизации и замена контроллеров магазина.
     */
    protected function overrideShopRoutes(): void
    {
        $router = app('router');

        $this->app->booted(function () use ($router) {
            $replaceControllers = [
                'shop.cart.index' => [\Azuriom\Plugin\ShopEasyReg\Overrides\CartController::class, 'index'],
                'shop.cart.update' => [\Azuriom\Plugin\ShopEasyReg\Overrides\CartController::class, 'update'],
                'shop.cart.remove' => [\Azuriom\Plugin\ShopEasyReg\Overrides\CartController::class, 'remove'],
                'shop.cart.clear' => [\Azuriom\Plugin\ShopEasyReg\Overrides\CartController::class, 'clear'],
                'shop.cart.payment' => [\Azuriom\Plugin\ShopEasyReg\Overrides\CartController::class, 'payment'],
                'shop.packages.buy' => [\Azuriom\Plugin\ShopEasyReg\Overrides\PackageController::class, 'buy'],
                'shop.packages.variables' => [\Azuriom\Plugin\ShopEasyReg\Overrides\PackageController::class, 'buy'],
                'shop.packages.show' => [\Azuriom\Plugin\ShopEasyReg\Overrides\PackageController::class, 'show'],
                'shop.packages.file' => [\Azuriom\Plugin\ShopEasyReg\Overrides\PackageController::class, 'downloadFile'],
            ];

            foreach ($replaceControllers as $name => $action) {
                $route = $router->getRoutes()->getByName($name);

                if ($route !== null) {
                    $route->uses([$action[0], $action[1]]);
                }
            }

            $removeAuth = [
                'shop.cart.index',
                'shop.cart.update',
                'shop.cart.remove',
                'shop.cart.clear',
                'shop.cart.payment',
                'shop.cart.coupons.add',
                'shop.cart.coupons.remove',
                'shop.cart.coupons.clear',
                'shop.cart.giftcards.add',
                'shop.cart.giftcards.remove',
                'shop.packages.buy',
                'shop.packages.variables',
            ];

            foreach ($removeAuth as $name) {
                $route = $router->getRoutes()->getByName($name);

                if ($route !== null) {
                    $middleware = array_values(array_diff($route->middleware(), ['auth']));

                    $route->middleware($middleware);
                }
            }
        });
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
