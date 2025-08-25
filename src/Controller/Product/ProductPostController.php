<?php

use Src\Service\Product\ProductCreatorService;

final readonly class ProductPostController {
    private ProductCreatorService $service;

    public function __construct() {
        $this->service = new ProductCreatorService();
    }

    public function start(): void {
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;
        $price = isset($_POST['price']) ? (float) $_POST['price'] : 0;
        $categoryId = isset($_POST['categoryId']) ? (int) $_POST['categoryId'] : 0;
        $variants = isset($_POST['variants']) ? json_decode($_POST['variants'], true) : [];

        // Manejo de imágenes
        $uploadedImageUrls = [];
        $uploadDir = '/var/www/html/public/uploads/products/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if(isset($_FILES['images'])) {
            $files = $_FILES['images'];
            
            // Verificar estructura de PHP para múltiples archivos
            if (is_array($files['name'])) {
                // MÚLTIPLES ARCHIVOS (images[])
                $fileCount = count($files['name']);
                
                for ($i = 0; $i < $fileCount; $i++) {
                    if(empty($files['tmp_name'][$i]) || $files['error'][$i] !== UPLOAD_ERR_OK) {
                        continue;
                    }

                    $originalName = basename($files['name'][$i]);
                    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    if(!in_array($ext, ['jpg','jpeg','png','webp'])) {
                        continue;
                    }

                    $filename = uniqid('prod_') . '.' . $ext;
                    $destination = $uploadDir . $filename;

                    if(move_uploaded_file($files['tmp_name'][$i], $destination)) {
                        $uploadedImageUrls[] = '/uploads/products/' . $filename;
                    }
                }
            } else {
                // UN SOLO ARCHIVO
                if(!empty($files['tmp_name']) && $files['error'] === UPLOAD_ERR_OK) {
                    $originalName = basename($files['name']);
                    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    if(in_array($ext, ['jpg','jpeg','png','webp'])) {
                        $filename = uniqid('prod_') . '.' . $ext;
                        $destination = $uploadDir . $filename;

                        if(move_uploaded_file($files['tmp_name'], $destination)) {
                            $uploadedImageUrls[] = '/uploads/products/' . $filename;
                        }
                    }
                }
            }
        }

        $createdAt = new DateTime();
        $updatedAt = new DateTime();

        $this->service->create(
            $name, $description, $price, $categoryId,
            $createdAt, $updatedAt, $variants, $uploadedImageUrls
        );

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Producto creado correctamente',
            'images_uploaded' => count($uploadedImageUrls),
            'images' => $uploadedImageUrls
        ]);
    }
}