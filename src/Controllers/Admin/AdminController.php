<?php

namespace Azuriom\Plugin\ShopEasyReg\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Отображение главной страницы администрирования плагина.
     */
    public function index()
    {
        return view('shopeasyreg::admin.index');
    }
}
