<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServersController extends AbstractController
{
    #[Route('/', name: 'servers_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('servers/index.html.twig');
    }
}
