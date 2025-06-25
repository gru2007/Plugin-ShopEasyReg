<?php

namespace Azuriom\Plugin\ShopEasyReg\Controllers;

use Azuriom\Http\Controllers\Controller;

class ShopEasyRegHomeController extends Controller
{
    /**
     * Пример пользовательской страницы плагина.
     */
    public function index()
    {
        return view('shopeasyreg::index');
    }
}
