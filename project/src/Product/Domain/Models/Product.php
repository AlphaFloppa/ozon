<?php

declare(strict_types=1);

namespace App\Product\Domain\Models;

use App\Product\Domain\Exceptions\PriceException;
use Symfony\Component\HttpFoundation\File\File;

class Product
{
    public function __construct(
        private ?int $id = null,
        private ?string $title = null,
        private ?float $price = null,
        private ?string $description = null,
        private ?File $image = null,
        //private string $sellerID
    )
    {

    }

    public function getId(): int | null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string | null
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPrice(): float | null
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->validatePrice($price);
        $this->price = $price;
    }

    public function getDescription(): string | null
    {
        return $this->description;
    }

    public function setDescription(string $desc): void
    {
        $this->description = $desc;
    }

    public function getImage(): File | null
    {
        return $this->image;
    }

    public function setImage(File $file): void
    {
        $this->image = $file;
    }

    private function validatePrice(float $price): void
    {
        if($price < 0){
            throw new PriceException($price);
        }
    }
}