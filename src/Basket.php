<?php
namespace Acme;

final class Basket
{
    private Catalogue $catalogue;
    private array $offers;
    private DeliveryCalculator $deliveryCalc;
    private array $items = [];

    public function __construct(Catalogue $catalogue, DeliveryCalculator $deliveryCalc, array $offers = [])
    {
        $this->catalogue = $catalogue;
        $this->offers = $offers;
        $this->deliveryCalc = $deliveryCalc;
    }

    public function add(string $productCode): void
    {
        if (!isset($this->items[$productCode])) {
            $this->items[$productCode] = 0;
        }
        $this->items[$productCode]++;
    }

    public function total(): float
    {
        $lines = [];
        foreach ($this->items as $code => $count) {
            $product = $this->catalogue->get($code);
            if ($product === null) throw new \InvalidArgumentException("Unknown product code: $code");
            $lines[$code] = [
                'count' => $count,
                'unitPrice' => $product->price,
                'lineTotal' => $product->price * $count,
            ];
        }
        foreach ($this->offers as $offer) $offer->apply($lines);
        $subtotal = array_sum(array_column($lines, 'lineTotal'));
        $delivery = $this->deliveryCalc->chargeFor($subtotal);
        return $this->truncateToCents($subtotal + $delivery);
    }

    private function truncateToCents(float $amount): float
    {
        return floor($amount * 100) / 100.0;
    }
}