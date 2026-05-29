<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Response\ServerResponse;
use App\Repository\ServerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/servers', name: 'api_servers_')]
class ServerController extends AbstractController
{
    public function __construct(
        private readonly ServerRepository $serverRepository,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);

        $filters = [
            'model' => $request->query->get('model'),
            'ram' => $request->query->get('ram'),
            'hdd' => $request->query->get('hdd'),
            'location' => $request->query->get('location'),
            'min_price' => $request->query->get('min_price'),
            'max_price' => $request->query->get('max_price'),
        ];

        $data = $this->serverRepository
            ->query('s')
            ->applyFilters($filters)
            ->orderBy('s.id', 'DESC')
            ->paginate($page);

        return $this->json(
            ServerResponse::paginated($data)
        );
    }
}
