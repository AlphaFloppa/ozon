<?php

declare(strict_types=1);

namespace App\Product\App\Model;

use Symfony\Component\HttpFoundation\File\File;

class SaveProductData
{
    public function __construct(
        private string $title,
        private float $price,
        private string $description,
        private File $image,
        private string $sellerId
    )
    {}

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

    public function getSellerId(): string
    {
        return $this->sellerId;
    }
}