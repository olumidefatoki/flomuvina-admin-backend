<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Utility\Authentication;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $auth = new Authentication();
        $resp = $auth->login($request);
        return response($resp, $resp['code']);
    }

    public function register(Request $request)
    {
        $auth = new Authentication();
        $resp = $auth->register($request);
        return response($resp, $resp['code']);
    }
}
