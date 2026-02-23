<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Query;

use App\Product\App\Query\ProductQueryServiceInterface;
use App\Product\Infrastructure\Exceptions\DatabaseException;
use PDO;
use Throwable;

class ProductQueryService implements ProductQueryServiceInterface
{
    public function __construct(
        private readonly PDO $pdo
    )
    {}

    /**
     * @throws DatabaseException
     */
    public function getProductsList(): array
    {
        $query = <<<SQL
            SELECT * 
            FROM product
            WHERE deleted_at IS NULL
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
                    fn($record) => $this->hydrateProductData($record),
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
     * @return array|null
     * @throws DatabaseException
     */
    public function findProductById(int $id): ?array
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

    private function hydrateProductData(array $data): array
    {
        return [
            'id' => $data['id'],
            'title' => $data['title'],
            'price' => (float) $data['price'],
            'description' => $data['description'],
            'image' => json_decode($data['images'])[0],
            'seller_id' => (string) $data['seller_id']
        ];
    }

}