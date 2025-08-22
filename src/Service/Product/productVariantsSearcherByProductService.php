<?php 

namespace Src\Service\Product;

use Src\Infrastructure\Repository\ProductVariant\ProductVariantRepository;

final readonly class ProductVariantsSearcherByProductService {

    private ProductVariantRepository $repository; 

    public function __construct() {
        $this->repository = new ProductVariantRepository();
    }

    public function search(int $id): array {  

        $productVariants = $this->repository->findVariantsByProductId($id);


        return $productVariants;
    }
}