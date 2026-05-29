<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Request\Server\IndexRequest;
use App\Response\ServerResponse;
use App\Repository\ServerRepository;
use App\Service\Server\ServerOptionsProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/servers', name: 'api_servers_')]
class ServerController extends AbstractController
{
    public function __construct(
        private readonly ServerRepository $serverRepository,
        private readonly ServerOptionsProvider $optionsProvider,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        #[MapQueryString] IndexRequest $request = new IndexRequest(),
    ): JsonResponse {
        $data = $this->serverRepository
            ->query('s')
            ->applyFilters($request->filters())
            ->orderByDesc('s.id')
            ->paginate($request->page);

        return $this->json(
            ServerResponse::paginated($data)
        );
    }

    #[Route('/options', name: 'options', methods: ['GET'])]
    public function options(): JsonResponse
    {
        return $this->json([
            'locationOptions' => $this->optionsProvider->getLocationOptions(),
            'ramValues' => $this->optionsProvider->getRamValues(),
            'harddiskTypes' => $this->optionsProvider->getHarddiskTypes(),
            'storageSlices' => $this->optionsProvider->getStorageSlices(),
        ]);
    }
}
