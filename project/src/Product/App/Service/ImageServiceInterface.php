<?php

declare(strict_types=1);

namespace App\Product\App\Service;

use Symfony\Component\HttpFoundation\File\File;

interface ImageServiceInterface
{

    /**
     * @return string адрес картинки на сервере для подгрузки (относительно public/)
     */
    public function getStorageDirectory(): string;
    
    /**
     * @param File $file файл картинки
     * @return File сохраненный в хранилище файл картинки
     */
    public function saveImage(File $file): File;

    /**
     * @param string $filename название файла картинки
     */
    public function deleteImage(string $filename): void;

    /**
     * @param string $previousFilename текущее название сохраненного файла картинки
     * @param File $newFile файл новой картинки
     * @return File файл сохраненной картинки (с другим названием)
     */
    public function updateImage(string $previousFilename, File $newFile): File;
}