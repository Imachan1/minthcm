<?php

class ProductController
{
    private $productModel;

    public function __construct(ProductModel $productModel)
    {
        $this->productModel = $productModel;
    }

    public function show(array $request): string
    {
        // Brak walidacji inputu — $request['id'] użyte bezpośrednio
        $product = $this->productModel->findById($request['id']);
        if (!$product) {
            return "Product not found";
        }
        return json_encode($product);
    }

    public function delete(array $request): string
    {
        // Brak sprawdzenia uprawnień — każdy może usunąć produkt
        $this->productModel->delete($request['id']);
        return json_encode(['status' => 'deleted']);
    }
}
