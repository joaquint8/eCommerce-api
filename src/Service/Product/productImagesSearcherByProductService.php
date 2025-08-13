<?php 

namespace Src\Service\Product;

use Src\Infrastructure\Repository\ProductImages\ProductImagesRepository;

final readonly class ProductImagesSearcherByProductService {

    private ProductImagesRepository $repository; 

    public function __construct() {
        $this->repository = new ProductImagesRepository();
    }

    public function search(int $id): array {  

        $productImages = $this->repository->findImagesByProductId($id);

       
        return $productImages;
    }
}