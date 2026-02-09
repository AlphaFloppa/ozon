<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Services;

use App\Product\App\Services\ImageServiceInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Product\Infrastructure\Exceptions\FileException;
use Throwable;

class ImageService implements ImageServiceInterface
{
    private string $actualDirectory = __DIR__ . '/../../../../public/product_images' . '/actual';
    private string $actualRenderDirectory = 'product_images/actual';
    private string $deletedDirectory = __DIR__ . '/../../../../public/product_images'. '/deleted';

    public function getStorageDirectory(): string
    {
        return $this->actualRenderDirectory;
    }

    public function saveImage(File $file): File
    {
        $newName = uniqid() . '.' . $file->guessExtension();
        try {
            return $file->move(
                $this->actualDirectory,
                $newName
            );
        } catch (Throwable $exception){
            throw new FileException('Internal error: ' . $exception->getMessage());
        }
    }

    public function deleteImage(string $filename): void
    {
        $filepath = $this->actualDirectory . '/' . $filename;
        try {           //не найдено - нечего удалять
            $file = new File($filepath, true);
        } catch (Throwable $excp) {
            return;
        }
        $file->move(
            $this->deletedDirectory
        );
    }

    public function updateImage(string $previousFilename, File $newFile): File
    {
        $previousFilepath = $this->actualDirectory . '/' . $previousFilename;
        unlink($previousFilepath);
        return $this->saveImage($newFile);
    }
}