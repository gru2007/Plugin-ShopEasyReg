<?php

namespace Azuriom\Plugin\ShopEasyReg\Overrides;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Shop\Cart\Cart;
use Azuriom\Plugin\Shop\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    /**
     * Добавление купона в корзину.
     */
    public function add(Request $request)
    {
        if ($request->user() === null) {
            return redirect()->back()->with('error', 'Необходимо войти, чтобы использовать купоны.');
        }

        $validated = $this->validate($request, ['coupon' => 'required']);

        $coupon = Coupon::active()->firstWhere('code', $validated['coupon']);

        if ($coupon === null || $coupon->hasReachLimit($request->user())) {
            throw ValidationException::withMessages([
                'coupon' => trans('shop::messages.coupons.error'),
            ]);
        }

        $cart = Cart::fromSession($request->session());

        if ((! $coupon->can_cumulate && ! $cart->coupons()->isEmpty())
            || $cart->coupons()->contains('can_cumulate', false)) {
            throw ValidationException::withMessages([
                'coupon' => trans('shop::messages.coupons.cumulate'),
            ]);
        }

        $cart->addCoupon($coupon);

        return to_route('shop.cart.index');
    }

    /**
     * Удаление купона из корзины.
     */
    public function remove(Request $request, Coupon $coupon)
    {
        if ($request->user() === null) {
            return redirect()->back()->with('error', 'Необходимо войти, чтобы использовать купоны.');
        }

        Cart::fromSession($request->session())->removeCoupon($coupon);

        return to_route('shop.cart.index');
    }

    /**
     * Очистка купонов в корзине.
     */
    public function clear(Request $request)
    {
        if ($request->user() === null) {
            return redirect()->back()->with('error', 'Необходимо войти, чтобы использовать купоны.');
        }

        Cart::fromSession($request->session())->clearCoupons();

        return to_route('shop.cart.index');
    }
}
