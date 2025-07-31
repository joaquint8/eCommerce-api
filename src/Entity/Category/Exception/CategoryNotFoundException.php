<?php 

namespace Src\Entity\Category\Exception;

use Exception;

final class CategoryNotFoundException extends Exception {
    public function __construct(int $id, string $customMessage = "") {
        $message = $customMessage ?: "No se pudo encontrar la categoria correspondiente. Id: ".$id;
        parent::__construct($message);
    }
}