<?php

namespace App\Service\Handlers;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Interface\CartInterface;
use Doctrine\ORM\EntityManagerInterface;

class CartHandler
{
    public function handle(CartItem $item, Cart $cart, CartInterface $strategy): Cart
    {
        return $strategy->add($item, $cart);
    }
}
