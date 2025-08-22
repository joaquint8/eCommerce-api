<?php 

namespace Src\Infrastructure\Repository\OrderDetail;

use Src\Entity\OrderDetail\OrderDetail;

interface OrderDetailRepositoryInterface {
    

    
    /** @return OrderDetail[] */
    public function search(): array;
    

}