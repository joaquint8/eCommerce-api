<?php 

namespace Src\Service\Product;
use Src\Infrastructure\Repository\ProductImages\ProductImagesRepository;

final readonly class productImagesSearcherByProductService {

    private ProductImagesRepository $repository; 

    public function __construct() {
        $this->repository = new ProductImagesRepository();
    }


    public function search($id): array
    {
        $images = $this->productImagesRepository->search();


        

        return $images;
    }
}
