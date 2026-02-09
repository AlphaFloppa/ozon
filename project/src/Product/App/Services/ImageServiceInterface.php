<?php

declare(strict_types=1);

namespace App\Product\App\Services;

use Symfony\Component\HttpFoundation\File\File;

interface ImageServiceInterface
{

    public function getStorageDirectory(): string;
    
    public function saveImage(File $file): File;

    public function deleteImage(string $filename): void;

    public function updateImage(string $previousFilename, File $newFile): File;
}