<?php

namespace App\Http\Controllers;

use Core\Http\Request;
use Core\Http\Response;

class StaticPageController extends Controller
{
    public function features(Request $request): Response
    {
        $html = file_get_contents(__DIR__ . '/../../../public/features.html');
        return new Response($html, 200);
    }
}
