<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServerRepository::class)]
#[ORM\Table(name: 'servers')]
class Server
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $model;

    #[ORM\Column(length: 32)]
    private string $ram;

    #[ORM\Column(length: 32)]
    private string $hdd;

    #[ORM\Column(length: 64)]
    private string $location;

    #[ORM\Column(length: 16)]
    private string $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getRam(): string
    {
        return $this->ram;
    }

    public function getHdd(): string
    {
        return $this->hdd;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getPrice(): string
    {
        return $this->price;
    }
}
