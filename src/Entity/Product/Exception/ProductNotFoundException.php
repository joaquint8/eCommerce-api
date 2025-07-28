<?php 

namespace Src\Entity\Product\Exception;

use Exception;

final class ProductNotFoundException extends Exception {
    public function __construct(int $id) {
        parent::__construct("No se encontro el producto correspondiente. Id: ".$id);
    }
}