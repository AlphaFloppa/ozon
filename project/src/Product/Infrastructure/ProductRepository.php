<?php

declare(strict_types=1);

namespace App\Product\Infrastructure;

use App\Product\App\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Product\Infrastructure\Exceptions\FileException;
use Throwable;

class ProductRepository implements ProductRepositoryInterface
{
    private string $publicDirectory = 'product_images';
    private string $fullDirectoryPath = __DIR__ . '/../../../public/product_images';

    public function getPublicImagesStorageDirectory(): string
    {
        return $this->publicDirectory;
    }

    /**
     * @param File $file
     * @throws FileException
     * @return File Имя сохраненного файла с расширением
     */
    public function store(File $file): File
    {
        $newName = uniqid() . '.' . $file->guessExtension();
        try {
            return $file->move(
                $this->fullDirectoryPath,
                $newName
            );
        } catch (Throwable $exception){
            throw new FileException('Internal error: ' . $exception->getMessage());
        }
    }

    public function delete(string $filename): void
    {
        $isSuccessful = unlink($this->fullDirectoryPath . '/' . $filename);
        if(!$isSuccessful){
            throw new FileException('Internal error');
        }
    }
}