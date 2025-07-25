<?php

namespace App\Http\Controllers;

use Core\Http\Request;
use Core\Http\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        return new Response('Welcome to Phuelone!', 200);
    }

    public function dashboard(Request $request): Response
    {
        // You can later use Auth::user() or middleware-verified info here
        return new Response('Dashboard page', 200);
    }
}

?>