<?php

namespace Azuriom\Plugin\ShopEasyReg\Controllers;

use Azuriom\Http\Controllers\Controller;

class ShopEasyRegHomeController extends Controller
{
    /**
     * Show the home plugin page.
     */
    public function index()
    {
        return view('shopeasyreg::index');
    }
}
