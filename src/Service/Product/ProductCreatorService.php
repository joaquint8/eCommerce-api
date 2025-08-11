<?php 

namespace Src\Service\Product;

use DateTime;
use Src\Entity\Product\Product;
use Src\Entity\Product\ProductState;
use Src\Entity\Product\ProductVariant;
use Src\Entity\Product\ProductImages;
use Src\Infrastructure\Repository\Product\ProductRepository;
use Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository;
use Src\Infrastructure\Repository\ProductImages\ProductImagesRepository;
final readonly class ProductCreatorService {
    private ProductRepository $repository;
    private ProductVariantRepository $productVariantRepository;
    private ProductImagesRepository $ProductImagesRepository;


    public function __construct() {
        $this->repository = new ProductRepository();
        $this->productVariantRepository = new ProductVariantRepository();
        $this->ProductImagesRepository = new ProductImagesRepository();
    }

    public function create(string $name, string $description, float $price, int $categoryId,array $variants = [], array $images = []): void
    {
        //Utiliza el Create de Entity, luego Insert de Repository
        $Product = Product::create($name, $description, $price, $categoryId);
        //$productId = $Product->id(); no funciona porque todavia no se creó el objeto

        $productId = $this->repository->insert($Product);

        //  Insertar variantes
        foreach ($variants as $variant) {
            $this->productVariantRepository->insert(
                new ProductVariant(null, $productId, $variant['color'], $variant['size'], $variant['stock'], ProductState::from($variant['state']),false)
            );
        }

        // Insertar imágenes
        foreach ($images as $image) {
            $this->ProductImagesRepository->insert(
                new ProductImages(null, $productId, $image['image_url'], new DateTime(), new DateTime(),false)
            );
        }
    }
}