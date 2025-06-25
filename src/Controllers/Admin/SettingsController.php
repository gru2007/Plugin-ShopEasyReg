<?php

namespace Azuriom\Plugin\ShopEasyReg\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Страница настроек плагина.
     */
    public function show()
    {
        return view('shopeasyreg::admin.settings');
    }

    /**
     * Сохранение настроек плагина.
     */
    public function save(Request $request)
    {
        Setting::updateSettings([
            'shop.cart_auth' => $request->has('cart_auth'),
            'shop.email_verification' => $request->has('email_verification'),
        ]);

        return redirect()->route('shopeasyreg.admin.index')
            ->with('success', trans('admin.settings.updated'));
    }
}
