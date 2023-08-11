<?php

namespace App\Controllers;

use App\Core\Response;

class SiteController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return new Response('Main page', 'Main page');
    }
}
