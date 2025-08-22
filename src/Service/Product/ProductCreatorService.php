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

final readonly class ProductCreatorService {
    private ProductRepository $productRepository;
    private ProductVariantRepository $variantRepository;
    private ProductImagesRepository $imagesRepository;

    public function __construct() {
        $this->productRepository = new ProductRepository();
        $this->variantRepository = new ProductVariantRepository();
        $this->imagesRepository = new ProductImagesRepository();
    }

    public function create(
        string $name,
        string $description,
        float $price,
        int $categoryId,
        DateTime $createdAt,
        DateTime $updatedAt,
        array $variants,
        array $images
    ): void {
        $product = Product::create($name, $description, $price, $categoryId, $createdAt, $updatedAt);

        $productId = $this->productRepository->insertAndReturnId($product);
        $product->setId($productId);

        foreach ($variants as $variant) {
            $variantEntity = new ProductVariant(
                null,
                $productId,
                ProductColor::from($variant["color"]),
                ProductSize::from($variant["size"]),
                (int) $variant["stock"],
                ProductState::ACTIVE,
                false
            );
            $this->variantRepository->insert($variantEntity);
        }

        foreach ($images as $url) {
            $imageEntity = new ProductImages(
                null,
                $productId,
                $url,
                $createdAt,
                $updatedAt,
                false
            );
            $this->imagesRepository->insert($imageEntity);
        }
    }
}
