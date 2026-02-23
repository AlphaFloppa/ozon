<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\App\Repository\ProductRepositoryInterface;
use PDO;
use App\Product\App\Models\Product;
use Throwable;
use App\Product\Infrastructure\Exceptions\DatabaseException;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    //в create придет product с id = null, в update без
    public function store(Product $product): void
    {
        $id = $product->getId();
        $query = $id
            ? <<<SQL
            UPDATE product
            SET title = :title, description = :description, price = :price, images = :image
            WHERE id = :id
        SQL
            : <<<SQL
            INSERT 
            INTO product
            (seller_id, title, price, description, images)
            VALUES
            (:seller_id, :title, :price, :description, :image)
        SQL;                //под создание нового
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new DatabaseException("Query is wrong");
        }
        try {
            if (
                !$stmt->execute(
                    [
                        ...($id ? ['id' => $product->getId()] : []),
                        ...($id ? [] : ['seller_id' => $product->getSellerId()]),
                        'title' => $product->getTitle(),
                        'price' => $product->getPrice(),
                        'description' => $product->getDescription(),
                        'image' => json_encode([$product->getImage()->getBasename()])
                    ]
                )
            ) {
                throw new DatabaseException("Internal error");
            }
        } catch (Throwable $exception) {
            throw new DatabaseException($exception->getMessage());
        }
    }

    public function delete(int $id): void
    {

        $query = <<<SQL
            UPDATE product
            SET deleted_at = NOW()
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
    }
    public function isProductExist(int $id): bool
    {
        $query = <<<SQL
            SELECT * 
            FROM product
            WHERE id = :id AND deleted_at IS NULL
        SQL;
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new DatabaseException("Query is wrong");
        }
        try {
            if ($stmt->execute(['id' => $id])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                return ((bool) $data) ? true : false;
            } else {
                throw new DatabaseException("Internal error");
            }
        } catch (Throwable $exception) {
            throw new DatabaseException($exception->getMessage());
        }
    }
}