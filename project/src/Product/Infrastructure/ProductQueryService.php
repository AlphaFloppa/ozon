<?php

declare(strict_types=1);

namespace App\Product\Infrastructure;

use App\Product\App\ProductQueryServiceInterface;
use App\Product\App\ProductData;
use PDO;

//прямое взаимодействие с бд
class ProductQueryService implements ProductQueryServiceInterface
{
    /**
     * @return ProductData[]
     */
    public function getProductsList(): array
    {
        $query = <<<SQL
            SELECT * 
            FROM product
            ORDER BY title ASC
        SQL;
        $pdo = ConnectionProvider::getConnectToDb();
        $request = $pdo->prepare($query);
        $request->execute();
        $response = $request->fetchAll(PDO::FETCH_ASSOC);
        $result = array_map(
            function($record)
            {
                return new ProductData(
                    $record['id'],
                    $record['title'],
                    floatval($record['price']),
                    $record['description'],
                    json_decode($record['images'])[0]
                );
            },
            $response
        );

        return $result;
    }

    public function findProduct(int $id): ?ProductData
    {
        $query = <<<SQL
            SELECT * 
            FROM product
            WHERE id = :id
        SQL;
        $pdo = ConnectionProvider::getConnectToDb();
        $request = $pdo->prepare($query);
        $request->execute(['id' => $id]);
        $response = $request->fetch(PDO::FETCH_ASSOC);
        if($response) {
            return new ProductData(
                $response['id'],
                $response['title'],
                floatval($response['price']),
                $response['description'],
                json_decode($response['images'])[0]
            );
        } else {
            return null;
        }
    }
}