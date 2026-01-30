<?php

declare(strict_types=1);

namespace App\Product\Infrastructure;

use App\Product\App\ProductRepositoryInterface;
use PDO;
use Symfony\Component\HttpFoundation\File\File;
use App\Product\Infrastructure\Exceptions\FileException;
use App\Product\Domain\Models\Product;
use Throwable;
use App\Product\Infrastructure\Exceptions\DatabaseException;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    )
    {}

    /*private string $publicDirectory = 'product_images';
    private string $fullDirectoryPath = __DIR__ . '/../../../public/product_images';

    public function getPublicImagesStorageDirectory(): string
    {
        return $this->publicDirectory;
    }*/

        //в create придет product с id = null, в update без
    public function store(Product $product): void
    {
        $id = $product->getId();
        $query = $id
        ? <<<SQL
            UPDATE product
            SET title = :title, description = :description, price = :price
            WHERE id = :id
        SQL
        : <<<SQL
            INSERT 
            INTO product
            (seller_id, title, price, description, images)
            VALUES
            (null, :title, :price, :description, null)
        SQL;                //под создание нового
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new DatabaseException("Query is wrong");
        }
        try {
            if (
                !$stmt->execute(
                    [
                        ...($id ? ['id' => $product->getId()]: []),
                        'title' => $product->getTitle(),
                        'price' => $product->getPrice(),
                        'description' => $product->getDescription(),
                    ]
                )
            ) {
                throw new DatabaseException("Internal error");
            }
        } catch (Throwable $exception) {
            throw new DatabaseException($exception->getMessage());
        }

        /*$newName = uniqid() . '.' . $file->guessExtension();
        try {
            return $file->move(
                $this->fullDirectoryPath,
                $newName
            );
        } catch (Throwable $exception){
            throw new FileException('Internal error: ' . $exception->getMessage());
        }*/
    }

    public function delete(int $id): void
    {

        $query = <<<SQL
            DELETE 
            FROM product
            WHERE id = :id 
        SQL;
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new DatabaseException("Query is wrong");
        }
        try {
            if (
                !$stmt->execute(
                    [
                        'id' => $id
                    ]
                )
            ) {
                throw new DatabaseException("Internal error");
            }
        } catch (Throwable $exception) {
            throw new DatabaseException($exception->getMessage());
        }
        /*$isSuccessful = unlink($this->fullDirectoryPath . '/' . $filename);
        if(!$isSuccessful){
            throw new FileException('Internal error');
        }*/
    }
}