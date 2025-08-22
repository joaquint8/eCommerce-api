<?php 

namespace Src\Infrastructure\Repository\Order;

use Src\Entity\Order\Order;

interface OrderRepositoryInterface {
    

    
    /** @return Order[] */
    public function search(): array;
    

}