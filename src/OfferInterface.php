<?php
namespace Acme;

interface OfferInterface
{
    public function apply(array &$lines): void;
}