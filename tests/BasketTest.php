<?php
namespace Acme\Tests;

use Acme\Catalogue;
use Acme\Product;
use Acme\Basket;
use Acme\DeliveryCalculator;
use Acme\BuyOneGetSecondHalfPrice;
use PHPUnit\Framework\TestCase;

final class BasketTest extends TestCase
{
    private Catalogue $catalogue;
    private DeliveryCalculator $delivery;
    private array $offers;

    protected function setUp(): void
    {
        $products = [
            new Product('R01', 'Red Widget', 32.95),
            new Product('G01', 'Green Widget', 24.95),
            new Product('B01', 'Blue Widget', 7.95),
        ];
        $this->catalogue = new Catalogue($products);
        $rules = [
            ['threshold' => 0.0, 'charge' => 4.95],
            ['threshold' => 50.0, 'charge' => 2.95],
            ['threshold' => 90.0, 'charge' => 0.0],
        ];
        $this->delivery = new DeliveryCalculator($rules);
        $this->offers = [ new BuyOneGetSecondHalfPrice('R01') ];
    }

    private function makeBasket(): Basket
    {
        return new Basket($this->catalogue, $this->delivery, $this->offers);
    }

    public function test_B01_G01_total()
    {
        $b = $this->makeBasket();
        $b->add('B01'); $b->add('G01');
        $this->assertSame(37.85, $b->total());
    }

    public function test_R01_R01_total()
    {
        $b = $this->makeBasket();
        $b->add('R01'); $b->add('R01');
        $this->assertSame(54.37, $b->total());
    }

    public function test_R01_G01_total()
    {
        $b = $this->makeBasket();
        $b->add('R01'); $b->add('G01');
        $this->assertSame(60.85, $b->total());
    }

    public function test_complex_basket()
    {
        $b = $this->makeBasket();
        $b->add('B01'); $b->add('B01');
        $b->add('R01'); $b->add('R01'); $b->add('R01');
        $this->assertSame(98.27, $b->total());
    }
}