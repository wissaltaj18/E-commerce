<?php

namespace App\DTO;

class CartItemDTO
{
    public function __construct(
        public int $productId,
        public int $quantity,
        public float $price
    ) {}
}