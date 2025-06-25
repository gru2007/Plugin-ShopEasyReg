<?php

namespace Azuriom\Plugin\ShopEasyReg\Controllers\Api;

use Azuriom\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * Пример ответа API плагина.
     */
    public function index()
    {
        return response()->json('Hello World!');
    }
}
