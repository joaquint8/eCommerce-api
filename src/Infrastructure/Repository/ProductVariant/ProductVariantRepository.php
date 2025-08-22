<?php

namespace Src\Infrastructure\Repository\ProductVariant;

use Src\Infrastructure\PDO\PDOManager;
use Src\Entity\Product\ProductVariant;
use Src\Entity\Product\ProductState;
use Src\Entity\Product\ProductColor;
use Src\Entity\Product\ProductSize;
use UnexpectedValueException;

final readonly class ProductVariantRepository extends PDOManager implements ProductVariantRepositoryInterface {

    public function findVariantsByProductId(int $productId): array
    {
        $query = <<<SQL
            SELECT *
            FROM Product_Variants PV
            WHERE PV.product_id = :product_id
            AND PV.deleted = 0
        SQL;

        $parameters = [
            "product_id" => $productId,
        ];

        $results = $this->execute($query, $parameters);

        $variants = [];
        foreach ($results as $row) {
            $variants[] = $this->toProductVariant($row);
        }

        return $variants;
    }

    /** @return ProductVariant[] */
    public function search(): array
    {
        $query = <<<OBTENER_VARIANTES
                        SELECT
                            *
                        FROM
                            Product_Variants PV
                        WHERE
                            PV.deleted = 0
                    OBTENER_VARIANTES;
        
        $results = $this->execute($query);

        $variants = [];
        foreach($results as $result) {
            $variants[] = $this->toProductVariant($result);
        }

        return $variants;
    }
    
    public function insert(ProductVariant $ProductVariant): void
    {
        $query = "INSERT INTO Product_Variants (product_id, color, size, stock, state, deleted) VALUES (:product_id, :color, :size, :stock, :state, :deleted) ";

        $parameters = [
            "product_id" => $ProductVariant->productId(),
            "color" => $ProductVariant->color()->value,
            "size" => $ProductVariant->size()->value,
            "stock" => $ProductVariant->stock(),
            "state" => $ProductVariant->state()->value,
            "deleted" => $ProductVariant->isDeleted()
        ];

        $this->execute($query, $parameters);
    }

    public function update(ProductVariant $variant): void {
        $query = <<<SQL
            UPDATE Product_Variants
            SET
                color = :color,
                size = :size,
                stock = :stock,
                state = :state,
                deleted = :deleted
            WHERE id = :id
        SQL;

        $parameters = [
            "id" => $variant->id(),
            "color" => $variant->color()->value,
            "size" => $variant->size()->value,
            "stock" => $variant->stock(),
            "state" => $variant->state()->value,
            "deleted" => $variant->isDeleted() ? 1 : 0
        ];

        $this->execute($query, $parameters);
    }

    /**
     * Intenta resolver un valor de color en su correspondiente enum ProductColor,
     * ignorando diferencias de mayúsculas/minúsculas.
     *
     * Ejemplo: "gris-claro", "Gris-Claro", "GRIS-CLARO" → ProductColor::GrisClaro
     *
     * @param string $input El valor de color recibido desde la base de datos o el frontend.
     * @return ProductColor|null El enum correspondiente, o null si no se encuentra coincidencia.
     */
    private function resolveColor(string $input): ?ProductColor
    {
        // Normalizamos el input: eliminamos espacios y lo convertimos a minúsculas
        $normalized = strtolower(trim($input));

        // Recorremos todos los casos definidos en el enum ProductColor
        foreach (ProductColor::cases() as $case) {
            // Comparamos el valor del enum (también en minúsculas) con el input normalizado
            if (strtolower($case->value) === $normalized) {
                return $case; // Si coincide, devolvemos el caso correspondiente
            }
        }

        return null; // Si no hay coincidencia, devolvemos null
    }

    public function deleteByProductId(int $productId): void {
        $query = <<<SQL
            UPDATE Product_Variants
            SET deleted = 1
            WHERE product_id = :product_id
        SQL;

        $this->execute($query, ["product_id" => $productId]);
    }

    public function restoreByProductId(int $productId): void {
        $query = <<<SQL
            UPDATE Product_Variants
            SET deleted = 0
            WHERE product_id = :product_id
        SQL;

        $this->execute($query, ["product_id" => $productId]);
    }

    public function physicalDeleteByProductId(int $productId): void {
        $query = <<<SQL
            DELETE FROM Product_Variants
            WHERE product_id = :product_id AND deleted = 1
        SQL;

        $this->execute($query, ["product_id" => $productId]);
    }

    /**
     * Convierte un array primitivo (por ejemplo, una fila de la base de datos)
     * en una instancia de ProductVariant, validando y resolviendo los enums.
     *
     * @param array|null $primitive La fila de datos a convertir.
     * @return ProductVariant|null El objeto construido, o null si el input es null.
     * @throws UnexpectedValueException Si alguno de los valores no es válido.
     */
    private function toProductVariant(?array $primitive): ?ProductVariant
    {
        if ($primitive === null) {
            return null; // Si no hay datos, devolvemos null
        }

        // Intentamos resolver el color usando el método tolerante a mayúsculas
        $color = $this->resolveColor($primitive['color']);

        // Si el color no es válido, lanzamos una excepción con el valor original
        if ($color === null) {
            throw new UnexpectedValueException("Color inválido: {$primitive['color']}");
        }

        // Construimos y devolvemos el objeto ProductVariant con los datos validados
        return new ProductVariant(
            $primitive["id"],                          // ID del variant
            $primitive["product_id"],                  // ID del producto
            $color,                                    // Enum de color resuelto
            ProductSize::from($primitive["size"]),     // Enum de tamaño (requiere coincidencia exacta)
            $primitive["stock"],                       // Stock disponible
            ProductState::from($primitive["state"]),   // Enum de estado (requiere coincidencia exacta)
            $primitive["deleted"]                      // Flag de borrado lógico
        );
    }
}

