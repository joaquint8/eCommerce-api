<?php
declare(strict_types=1);

// Importa el servicio que se encarga de buscar productos desde la base de datos
use Src\Service\Product\ProductsSearcherService;
use Src\Entity\Product\Product;

// Controlador encargado de manejar la petición GET de productos
final readonly class ProductsGetController {
    private ProductsSearcherService $service;

    // Constructor: inicializa el servicio de búsqueda
    public function __construct() {
        $this->service = new ProductsSearcherService();
    }

    // Método para limpiar y asegurar codificación UTF-8 en strings
    private function cleanString(string $text): string {
        return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    }

    // Método principal que se ejecuta al invocar el controlador
    public function start(): void {
        try {
            // Busca los productos usando el servicio
            $products = $this->service->search();

            // Transforma los productos en un array listo para serializar
            $response = $this->toResponse($products);

            // Define el header para que la respuesta sea interpretada como JSON
            header('Content-Type: application/json');

            // Devuelve la respuesta en formato JSON, sin escapar caracteres Unicode
            echo json_encode($response, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            // En caso de error, devuelve un mensaje útil y código 500
            http_response_code(500);
            echo json_encode([
                "error" => "Error al procesar productos",
                "message" => $e->getMessage()
            ]);
        }
    }

    // Transforma una lista de entidades Product en un array serializable
    private function toResponse(array $products): array {
        $responses = [];

        foreach ($products as $product) {
            $responses[] = [
                "id" => $product->id(),
                "name" => $this->cleanString($product->name()),
                "description" => $this->cleanString($product->description()),
                "price" => $product->price(),
                "categoryId" => $product->categoryId(),
                "deleted" => $product->isDeleted(),
                "created_at" => $product->created_at()->format('Y-m-d H:i:s'),
                "updated_at" => $product->updated_at()->format('Y-m-d H:i:s'),
                "images" => $this->loadImages($product),     // Carga imágenes asociadas
                "variants" => $this->loadVariants($product)  // Carga variantes asociadas
            ];
        }

        return $responses;
    }

    // Extrae y formatea las imágenes del producto
    private function loadImages(Product $product): array {
        $images = [];

        foreach ($product->getImages() as $image) {
            $images[] = [
                "id" => $image->id(),
                "product_id" => $image->productId(),
                "image_url" => $this->cleanString($image->image_url()),
                "created_at" => $image->created_at()->format('Y-m-d H:i:s'),
                "updated_at" => $image->updated_at()->format('Y-m-d H:i:s'),
                "deleted" => $image->isDeleted()
            ];
        }

        return $images;
    }

    // Extrae y formatea las variantes del producto
    private function loadVariants(Product $product): array {
        $variants = [];

        foreach ($product->getVariants() as $variant) {
            $variants[] = [
                "id" => $variant->id(),
                "product_id" => $variant->productId(),
                "color" => $variant->color()->value,
                "size" => $variant->size()->value,
                "stock" => $variant->stock(),
                "state" => $variant->state()->value,
                "deleted" => $variant->isDeleted()
            ];
        }

        return $variants;
    }
}