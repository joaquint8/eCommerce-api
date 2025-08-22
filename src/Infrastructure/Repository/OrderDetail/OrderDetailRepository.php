<?php

namespace Src\Infrastructure\Repository\OrderDetail;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\OrderDetail\OrderDetail;
use DateTime;

final readonly class OrderDetailRepository extends PDOManager implements OrderDetailRepositoryInterface {
    
    public function find(int $id): ?OrderDetail {
        $query = <<<SQL
            SELECT *
            FROM Order_Detail OD
            WHERE OD.id = :id
        SQL;

        $parameters = ["id" => $id];

        $result = $this->execute($query, $parameters);

        return $this->toOrderDetail($result[0] ?? null);
    }

    /** @return OrderDetail[] */
    public function search(): array{

        $query = <<<OBTENER_CARRITOS
                        SELECT
                            *
                        FROM
                            Order_Detail
                        
                    OBTENER_CARRITOS;
        
        $results = $this->execute($query);//Método heredado de PDOManager.Retorna un array asociativo con los resultados

        $OrderDetail = [];
        foreach($results as $result) { //Convierte cada resultado en un objeto Order
            $OrderDetail[] = $this->toOrderDetail($result);
        }

        return $OrderDetail;
    }
    
    private function toOrderDetail(?array $primitive): ?OrderDetail {
        if ($primitive === null) {
            return null;
        }

        return new OrderDetail(
            $primitive["id"],
            $primitive["order_id"],
            $primitive["product_id"],
            $primitive["quantity"],
            $primitive["unit_price"],
            $primitive["total_price"],
            new DateTime($primitive["created_at"]),
            new DateTime($primitive["updated_at"])
        );
    }
}