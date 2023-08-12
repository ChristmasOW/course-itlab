<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class SiteController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return new Response('Main page', 'Main page');
    }
}
