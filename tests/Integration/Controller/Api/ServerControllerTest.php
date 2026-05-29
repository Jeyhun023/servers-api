<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller\Api;

use App\Tests\Fixtures\ServerFactory;
use App\Tests\Support\ResetsSchemaTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ServerControllerTest extends WebTestCase
{
    use ResetsSchemaTrait;

    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);

        $this->resetSchema($this->entityManager);

        $cache = $container->get('cache.app');
        $cache->clear();
    }

    public function testIndexReturnsPaginatedShape(): void
    {
        ServerFactory::persistMany($this->entityManager, [
            [
                'model' => 'A',
                'ram' => '4GB',
                'hdd' => '1x500GB',
                'location' => 'AMS',
                'price' => '10'
            ],
            [
                'model' => 'B',
                'ram' => '8GB',
                'hdd' => '1x1TB',
                'location' => 'WAS',
                'price' => '20'
            ],
        ]);

        $this->client->request('GET', '/api/servers');

        self::assertResponseIsSuccessful();
        $payload = $this->decode();

        $this->assertSame(['data', 'meta'], array_keys($payload));
        $this->assertCount(2, $payload['data']);
        $this->assertSame(2, $payload['meta']['total']);
        $this->assertSame(1, $payload['meta']['current_page']);
        $this->assertSame(10, $payload['meta']['per_page']);
    }

    public function testIndexAppliesLocationAndRamFilters(): void
    {
        ServerFactory::persistMany($this->entityManager, [
            [
                'model' => 'A',
                'ram' => '4GB',
                'hdd' => '1x500GB',
                'location' => 'AMS',
                'price' => '10'
            ],
            [
                'model' => 'B',
                'ram' => '8GB',
                'hdd' => '1x1TB',
                'location' => 'AMS',
                'price' => '20'
            ],
            [
                'model' => 'C',
                'ram' => '8GB',
                'hdd' => '1x1TB',
                'location' => 'WAS',
                'price' => '30'
            ],
        ]);

        $this->client->request('GET', '/api/servers', [
            'location' => 'AMS',
            'ram' => ['8GB'],
        ]);

        self::assertResponseIsSuccessful();
        $payload = $this->decode();

        $this->assertCount(1, $payload['data']);
        $this->assertSame('B', $payload['data'][0]['model']);
    }

    public function testIndexAppliesStorageRangeFilter(): void
    {
        ServerFactory::persistMany($this->entityManager, [
            [
                'model' => 'small',
                'ram' => '4GB',
                'hdd' => '1x250GB',
                'location' => 'AMS',
                'price' => '10'
            ],
            [
                'model' => 'medium',
                'ram' => '4GB',
                'hdd' => '2x500GB',
                'location' => 'AMS',
                'price' => '20'
            ],
            [
                'model' => 'large',
                'ram' => '4GB',
                'hdd' => '4x2TB',
                'location' => 'AMS',
                'price' => '30'
            ],
        ]);

        $this->client->request('GET', '/api/servers', [
            'min_storage' => 500,
            'max_storage' => 2000,
        ]);

        self::assertResponseIsSuccessful();
        $payload = $this->decode();
        $models = array_column($payload['data'], 'model');

        $this->assertSame(['medium'], $models);
    }

    public function testIndexRejectsNegativeMinStorage(): void
    {
        $this->client->request('GET', '/api/servers', ['min_storage' => -1]);

        $this->assertSame(422, $this->client->getResponse()->getStatusCode());
    }

    public function testOptionsReturnsExpectedKeysAndDistinctLocations(): void
    {
        ServerFactory::persistMany($this->entityManager, [
            [
                'model' => 'A',
                'ram' => '4GB',
                'hdd' => '1x500GB',
                'location' => 'WAS',
                'price' => '10'
            ],
            [
                'model' => 'B',
                'ram' => '4GB',
                'hdd' => '1x500GB',
                'location' => 'AMS',
                'price' => '20'
            ],
            [
                'model' => 'C',
                'ram' => '4GB',
                'hdd' => '1x500GB',
                'location' => 'AMS',
                'price' => '30'
            ],
        ]);

        $this->client->request('GET', '/api/servers/options');

        self::assertResponseIsSuccessful();
        $payload = $this->decode();

        $this->assertSame(
            ['locationOptions', 'ramValues', 'harddiskTypes', 'storageSlices'],
            array_keys($payload),
        );
        $this->assertSame(['AMS', 'WAS'], $payload['locationOptions']);
        $this->assertSame(['SAS', 'SATA', 'SSD'], $payload['harddiskTypes']);
    }

    private function decode(): array
    {
        return json_decode(
            $this->client->getResponse()->getContent(), 
            true, 
            flags: JSON_THROW_ON_ERROR
        );
    }
}
