<?php

namespace Azuriom\Plugin\ShopEasyReg\Overrides;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Shop\Cart\Cart;
use Azuriom\Plugin\Shop\Models\Package;
use Azuriom\Support\Markdown;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Отображение корзины пользователя.
     */
    public function index(Request $request)
    {
        // Если пользователь не авторизован, запоминаем адрес корзины для
        // перенаправления после регистрации или входа
        if (auth()->guest()) {
            $request->session()->put('url.intended', route('shop.cart.index'));
        }


        $terms = setting('shop.required_terms');

        if ($terms !== null) {
            $markdown = Markdown::parse($terms, true);

            $terms = new HtmlString(Str::between($markdown, '<p>', '</p>'));
        }

        return view('shop::cart.index', [
            'cart' => Cart::fromSession($request->session()),
            'terms' => $terms,
            // Передаем настройки капчи для форм авторизации и регистрации
            'captchaLogin' => (bool) setting('captcha.login'),
            'captchaRegister' => setting('captcha.type') !== null,
        ]);
    }

    /**
     * Удаление товара из корзины.
     */
    public function remove(Request $request, Package $package)
    {

        $cart = Cart::fromSession($request->session());

        $cart->remove($package);

        return to_route('shop.cart.index');
    }

    /**
     * Обновление количества товаров в корзине.
     */
    public function update(Request $request)
    {

        $cart = Cart::fromSession($request->session());

        foreach ($request->input('quantities', []) as $id => $quantity) {
            $item = $cart->getById($id);

            if ($item !== null && $quantity > 0) {
                $item->setQuantity($quantity);
            }

            $cart->save();
        }

        return to_route('shop.cart.index');
    }

    /**
     * Очистка корзины.
     */
    public function clear(Request $request)
    {

        Cart::fromSession($request->session())->clear();

        return to_route('shop.cart.index');
    }

    /**
     * Оплата с помощью валюты сайта.
     */
    public function payment(Request $request)
    {
        if (! use_site_money()) {
            return to_route('shop.cart.index');
        }

        $cart = Cart::fromSession($request->session());

        if ($cart->isEmpty()) {
            return to_route('shop.cart.index');
        }

        $user = $request->user();
        $total = $cart->payableTotal();

        if (! $user->hasMoney($total)) {
            return to_route('shop.cart.index')->with('error', trans('shop::messages.cart.errors.money'));
        }

        $user->removeMoney($total);

        try {
            payment_manager()->buyPackages($cart);
        } catch (Exception $e) {
            report($e);

            $user->addMoney($total);

            return to_route('shop.cart.index')->with('error', trans('shop::messages.cart.errors.execute'));
        }

        $cart->destroy();

        return to_route('shop.home')->with('success', trans('shop::messages.cart.success'));
    }
}
