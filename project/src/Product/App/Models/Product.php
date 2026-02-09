<?php

declare(strict_types=1);

namespace App\Product\App\Models;

use Symfony\Component\HttpFoundation\File\File;

class Product
{
    public function __construct(
        private ?int $id = null,
        private string $title,
        private float $price,
        private string $description,
        private File $image,
        //private string $sellerID
    )
    {
    }

    public function getId(): int | null
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): File
    {
        return $this->image;
    }
}