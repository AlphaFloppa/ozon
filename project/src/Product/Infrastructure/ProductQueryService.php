<?php

declare(strict_types=1);

namespace App\Product\Infrastructure;

use App\Product\App\ProductData;
use App\Product\App\ProductQueryServiceInterface;
use App\Product\Domain\Models\Product;
use App\Product\Infrastructure\Exceptions\DatabaseException;
use PDO;
use Throwable;

//прямое взаимодействие с бд
class ProductQueryService implements ProductQueryServiceInterface
{
    public function __construct(
        private readonly PDO $pdo
    )
    {}

    private function hydrateProductData(array $data): ProductData
    {
        return new ProductData(
            $data['id'],
            $data['title'],
            floatval($data['price']),
            $data['description'],
            json_decode($data['images'])[0]
        );
    }

    /**
     * @return ProductData[]
     * @throws DatabaseException
     */
    public function getProductsList(): array
    {
        $query = <<<SQL
            SELECT * 
            FROM product
            ORDER BY title ASC
        SQL;
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new DatabaseException("Query is wrong");
        }
        try {
            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return array_map(
                    function($record)
                    {
                        return $this->hydrateProductData($record);
                    },
                    $data
                );
            } else {
                throw new DatabaseException("Internal database error");
            }
        } catch (Throwable $exception) {
            throw new DatabaseException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return ProductData|null
     * @throws DatabaseException
     */
    public function findProduct(int $id): ?ProductData
    {
        $query = <<<SQL
            SELECT * 
            FROM product
            WHERE id = :id
        SQL;
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new DatabaseException("Query is wrong");
        }
        try {
            if ($stmt->execute(['id' => $id])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if($data) {
                    return $this->hydrateProductData($data);
                } else {
                    return null;                //не найдено такого
                }
            } else {
                throw new DatabaseException("Internal error");
            }
        } catch (Throwable $exception) {
            throw new DatabaseException($exception->getMessage());
        }
    }

    public function getProductImages(int $id): ?array
    {
        $query = <<<SQL
            SELECT images 
            FROM product
            WHERE id = (:id)
        SQL;
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new DatabaseException("Query is wrong");
        }
        try {
            if ($stmt->execute(['id' => $id])) {
                $data = $stmt->fetch(PDO::FETCH_COLUMN);
                if ($data) {
                    return json_decode($data);
                } else {
                    return null;                //не найдено такого
                }
            } else {
                throw new DatabaseException("Internal error");
            }
        } catch (Throwable $exception) {
            throw new DatabaseException($exception->getMessage());
        }
    }

    /**
     * @param Product $product
     * @return void
     * @throws DatabaseException
     */
    public function createProduct(Product $product): void
    {
        $query = <<<SQL
            INSERT 
            INTO product
            (seller_id, title, price, description, images)
            VALUES
            (null, :title, :price, :description, :image)
        SQL;
        $stmt = $this->pdo->prepare($query);
        if (!$stmt) {
            throw new DatabaseException("Query is wrong");
        }
        try {
            if (
                !$stmt->execute(
                    [
                        'title' => $product->getTitle(),
                        'price' => $product->getPrice(),
                        'description' => $product->getDescription(),
                        'image' => json_encode(
                            [
                                $product->getImage()->getBasename()
                            ]
                        )
                    ]
                )
            ) {
                throw new DatabaseException("Internal error");
            }
        } catch (Throwable $exception) {
            throw new DatabaseException($exception->getMessage());
        }
    }

    public function deleteProduct(int $id): void
    {
        $query = <<<SQL
            DELETE 
            FROM product
            WHERE id = (:id) 
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
}