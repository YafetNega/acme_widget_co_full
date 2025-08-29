<?php
namespace Acme;

final class DeliveryCalculator
{
    private array $rules;
    public function __construct(array $rules) { $this->rules = $rules; }

    public function chargeFor(float $subtotal): float
    {
        $applicable = null;
        foreach ($this->rules as $rule) {
            if ($subtotal >= $rule['threshold']) {
                $applicable = $rule['charge'];
            }
        }
        if ($applicable === null) {
            return end($this->rules)['charge'] ?? 0.0;
        }
        return $applicable;
    }
}