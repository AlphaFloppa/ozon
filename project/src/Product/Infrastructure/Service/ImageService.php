<?php

declare (strict_types = 1);

namespace App\Product\Infrastructure\Service;

use App\Product\App\Service\ImageServiceInterface;
use Symfony\Component\HttpFoundation\File\File;
use Throwable;

class ImageService implements ImageServiceInterface
{
    private string $actualDirectory;

    private string $actualRenderDirectory = 'product_images/actual';

    private string $deletedDirectory;

    private string $rootDirectory = __DIR__ . '/../../../../public/product_images';

    public function __construct()
    {
        $this->actualDirectory = $this->rootDirectory . '/actual';
        $this->deletedDirectory = $this->rootDirectory . '/deleted';
    }

    public function deleteImage(string $filename): void
    {
        $filepath = $this->actualDirectory . '/' . $filename;

        try {
            //не найдено - нечего удалять
            $file = new File($filepath, true);
        }
        catch (Throwable $excp)
        {
            return;
        }

        $file->move(
            $this->deletedDirectory
        );
    }

    public function getStorageDirectory(): string
    {
        return $this->actualRenderDirectory;
    }

    public function saveImage(File $file): File
    {
        $newName = uniqid() . '.' . $file->guessExtension();

        return $file->move(
            $this->actualDirectory,
            $newName
        );
    }

    public function updateImage(string $previousFilename, File $newFile): File
    {
        $previousFilepath = $this->actualDirectory . '/' . $previousFilename;
        unlink($previousFilepath);

        return $this->saveImage($newFile);
    }
}
