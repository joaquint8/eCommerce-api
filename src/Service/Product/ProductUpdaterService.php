<?php

namespace Src\Service\Product;

use DateTime;
use Src\Entity\Product\Product;
use Src\Entity\Product\ProductVariant;
use Src\Entity\Product\ProductImages;
use Src\Entity\Product\ProductColor;
use Src\Entity\Product\ProductSize;
use Src\Entity\Product\ProductState;
use Src\Infrastructure\Repository\Product\ProductRepository;
use Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository;
use Src\Infrastructure\Repository\ProductImages\ProductImagesRepository;

final readonly class ProductUpdaterService {
    private ProductRepository $productRepository;
    private ProductVariantRepository $variantRepository;
    private ProductImagesRepository $imagesRepository;

    public function __construct() {
        $this->productRepository = new ProductRepository();
        $this->variantRepository = new ProductVariantRepository();
        $this->imagesRepository = new ProductImagesRepository();
    }

    public function update(
        int $productId,
        string $name,
        string $description,
        float $price,
        int $categoryId,
        DateTime $updatedAt,
        array $variants,
        array $images
    ): void {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new \InvalidArgumentException("Producto no encontrado: ID {$productId}");
        }

        // Actualizar datos del producto
        $product->modify($name, $description, $price, $categoryId, $updatedAt);
        $this->productRepository->update($product);

        // Actualizar o insertar variantes
        foreach ($variants as $variant) {
            $variantEntity = new ProductVariant(
                $variant["id"] ?? null,
                $productId,
                ProductColor::from($variant["color"]),
                ProductSize::from($variant["size"]),
                (int) $variant["stock"],
                ProductState::from($variant["state"] ?? "active"),
                false
            );

            if (isset($variant["id"])) {
                $this->variantRepository->update($variantEntity);
            } else {
                $this->variantRepository->insert($variantEntity);
            }
        }

        // Actualizar o insertar imágenes
        // foreach ($images as $image) {
        //     $imageEntity = new ProductImages(
        //         $image["id"] ?? null,
        //         $productId,
        //         $image["url"],
        //         $product->created_at(),
        //         $updatedAt,
        //         false
        //     );

        //     if (isset($image["id"])) {
        //         $this->imagesRepository->update($imageEntity);
        //     } else {
        //         $this->imagesRepository->insert($imageEntity);
        //     }
        // }

        foreach ($images as $imageUrl) {
            $imageEntity = new ProductImages(
                null,
                $productId,
                $imageUrl,
                $product->created_at(),
                $updatedAt,
                false
            );

            $this->imagesRepository->insert($imageEntity);
        }

    }
}
