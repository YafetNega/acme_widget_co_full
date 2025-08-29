<?php
namespace Acme;

final class BuyOneGetSecondHalfPrice implements OfferInterface
{
    private string $targetCode;

    public function __construct(string $targetCode)
    {
        $this->targetCode = $targetCode;
    }

    public function apply(array &$lines): void
    {
        if (!isset($lines[$this->targetCode])) {
            return;
        }
        $count = $lines[$this->targetCode]['count'];
        $unitPrice = $lines[$this->targetCode]['unitPrice'];
        $pairs = intdiv($count, 2);
        $remainder = $count % 2;
        $pairTotal = $pairs * ($unitPrice + ($unitPrice / 2.0));
        $remainderTotal = $remainder * $unitPrice;
        $lines[$this->targetCode]['lineTotal'] = $pairTotal + $remainderTotal;
    }
}