<?php

declare(strict_types=1);

namespace App\Product\Domain\Models;

class Product
{
    public function __construct(
        private int $id,
        private string $name,
        private float $price,
        private string $description,
        private ?string $image,
        //private string $sellerID 
    )
    {
        $this->validatePrice($price);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    private function validatePrice(float $price): void
    {
        if($price < 0){
            throw new PriceException();
        }
    }
}