<?php

namespace Azuriom\Plugin\ShopEasyReg\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Show the home admin page of the plugin.
     */
    public function index()
    {
        return view('shopeasyreg::admin.index');
    }
}
