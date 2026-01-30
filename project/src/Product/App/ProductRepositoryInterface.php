<?php

declare(strict_types=1);

namespace App\Product\App;

use Symfony\Component\HttpFoundation\File\File;

interface ProductRepositoryInterface
{

    public function getPublicImagesStorageDirectory(): string;
    
    public function store(File $file): File;

    public function delete(string $filename): void;
}