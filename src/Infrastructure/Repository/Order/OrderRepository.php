<?php 

namespace Src\Infrastructure\Repository\Order;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Order\Order;
use Src\Entity\Order\OrderState;
use DateTime;

final readonly class OrderRepository extends PDOManager implements OrderRepositoryInterface {
    
    /** @return Order[] */
    public function search(): array{

        $query = <<<OBTENER_PEDIDOS
                        SELECT
                            *
                        FROM
                            Orders O
                        
                    OBTENER_PEDIDOS;
        
        $results = $this->execute($query);//Método heredado de PDOManager.Retorna un array asociativo con los resultados

        $Order = [];
        foreach($results as $result) { //Convierte cada resultado en un objeto Order
            $Order[] = $this->toOrder($result);
        }

        return $Order;
    }
    
    private function toOrder(?array $primitive): ?Order {
        if ($primitive === null) {
            return null;
        }

        return new Order(
            $primitive["id"],
            $primitive["total"],
            $primitive["shipping_address"],
            OrderState::from($primitive["status"]),
            new DateTime($primitive["created_at"])
        );
    }
}