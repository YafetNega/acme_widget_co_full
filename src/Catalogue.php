<?php
namespace Acme;

final class Catalogue
{
    private array $products = [];

    public function __construct(array $products = [])
    {
        foreach ($products as $product) {
            $this->products[$product->code] = $product;
        }
    }

    public function get(string $code): ?Product
    {
        return $this->products[$code] ?? null;
    }
}