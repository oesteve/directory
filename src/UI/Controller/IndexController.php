<?php

namespace Directory\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}
