<?php
use Src\Service\Product\ProductsSearcherService;
use Src\Entity\Product\Product;

final readonly class ProductsGetController {
    private ProductsSearcherService $service; 

    public function __construct() {
        $this->service = new ProductsSearcherService(); 
    }

    public function start(): void{
        $Product = $this->service->search();

        echo json_encode($this->toResponse($Product));
    }

    private function toResponse(array $Product): array{
        $responses = [];
        
        foreach($Product as $product) {

            $images = $this->loadImages($product);
            $variants = $this->loadVariants($product);

            $responses[] = [
                "id" => $product->id(),
                "name" => $product->name(),
                "description" => $product->description(),
                "price" => $product->price(),
                "categoryId" => $product->categoryId(),
                "deleted" => $product->isDeleted(),
                "created_at" => $product->created_at()->format('Y-m-d H:i:s'),
                "updated_at" => $product->updated_at()->format('Y-m-d H:i:s'),
                "images" => $images,
                "variants" => $variants
            ];
        }

        return $responses;
    }

    private function loadImages(Product $product): array
    {
        $images = [];
        foreach ($product->getImages() as $image) {
            $images[] = [
                "id" => $image->id(),
                "product_id" => $image->productId(),
                "image_url" => $image->image_url(),
                "created_at" => $image->created_at()->format('Y-m-d H:i:s'),
                "updated_at" => $image->updated_at()->format('Y-m-d H:i:s'),
                "deleted" => $image->isDeleted()
            ];
        }
        return $images;
    }

    private function loadVariants(Product $product): array
    {
        $variants = [];
        foreach ($product->getVariants() as $variant) {
            $variants[] = [
                "id" => $variant->id(),
                "product_id" => $variant->productId(),
                "color" => $variant->color(),
                "size" => $variant->size(),
                "stock" => $variant->stock(),
                "state" => $variant->state()->value,
                "deleted" => $variant->isDeleted()
            ];
        }
        return $variants;
    }
}