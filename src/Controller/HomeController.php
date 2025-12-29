<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends BaseController
{
    public const CONTROLLER_NAME = 'HomeController';

    #[Route(path: '/', name: 'app_home', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->renderWithController('home/index.html.twig');
    }
}
