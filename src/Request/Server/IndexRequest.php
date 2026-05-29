<?php

declare(strict_types=1);

namespace App\Request\Server;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class IndexRequest
{
    public function __construct(
        public ?string $model = null,
        public ?string $ram = null,
        public ?string $hdd = null,
        public ?string $location = null,

        #[SerializedName('min_price')]
        #[Assert\PositiveOrZero]
        public ?float $minPrice = null,

        #[SerializedName('max_price')]
        #[Assert\PositiveOrZero]
        public ?float $maxPrice = null,

        #[Assert\Positive]
        public int $page = 1,
    ) {
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'ram' => $this->ram,
            'hdd' => $this->hdd,
            'location' => $this->location,
            'min_price' => $this->minPrice,
            'max_price' => $this->maxPrice,
        ];
    }
}
