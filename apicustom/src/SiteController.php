<?php

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
