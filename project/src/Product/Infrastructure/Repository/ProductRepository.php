<?php

declare (strict_types = 1);

namespace App\Product\Infrastructure\Repository;

use App\Product\App\Model\Product;
use App\Product\App\Model\SaveProductData;
use App\Product\App\Repository\ProductRepositoryInterface;
use Exception;
use PDO;

class ProductRepository implements ProductRepositoryInterface
{
    
    public function __construct(
        private PDO $pdo
    )
    {}

    public function delete(int $id): void
    {

        $query = <<<SQL
            UPDATE product
            SET deleted_at = NOW()
            WHERE id = :id
        SQL;
        $stmt = $this->pdo->prepare($query);
        if (! $stmt)
        {
            throw new Exception("Query is wrong");
        }
        if (
            ! $stmt->execute(
                [
                    'id' => $id,
                ]
            )
        )
        {
            throw new Exception("Internal error");
        }
    }

    public function findProduct(int $id): array | null
    {
        $query = <<<SQL
            SELECT *
            FROM product
            WHERE id = :id AND deleted_at IS NULL
        SQL;
        $stmt = $this->pdo->prepare($query);
        if (! $stmt)
        {
            throw new Exception("Query is wrong");
        }
        if ($stmt->execute(['id' => $id]))
        {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data)
            {
                return [
                    'id' => $data['id'],
                    'title' => $data['title'],
                    'price' => floatval($data['price']),
                    'description' => $data['description'],
                    'image' => json_decode($data['images'])[0],
                ];
            }
            else
            {
                return null;
            }
        }
        else
        {
            throw new Exception("Internal error");
        }
    }

    public function store(SaveProductData | Product $product): void
    {
        $isProductNew = $product instanceof Product;
        $query = $isProductNew
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
            (null, :title, :price, :description, :image)
        SQL;
        $stmt = $this->pdo->prepare($query);
        if (! $stmt)
        {
            throw new Exception("Query is wrong");
        }
        if (
            ! $stmt->execute(
                [
                     ...($isProductNew ? ['id' => $product->getId()] : []),
                    'title' => $product->getTitle(),
                    'price' => $product->getPrice(),
                    'description' => $product->getDescription(),
                    'image' => json_encode([$product->getImage()->getBasename()])
                ]
            )
        )
        {
            throw new Exception("Internal error");
        }
    }
}
