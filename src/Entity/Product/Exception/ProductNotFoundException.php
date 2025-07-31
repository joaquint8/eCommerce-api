<?php 

namespace Src\Entity\Product\Exception;

use Exception;

final class ProductNotFoundException extends Exception {
    public function __construct(int $id, string $customMessage = "") {
        $message = $customMessage ?: "No se pudo encontrar el producto correspondiente. Id: ".$id;
        parent::__construct($message);
    }
}