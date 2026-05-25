<?php
namespace App\Service\Handlers;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Interface\CartInterface;

class ApiCart implements CartInterface {

    public function getCart(string $identifier): Cart
    {
        $cart = new Cart();
        $cart->setId($identifier);
        return $cart;
    }

    public function add(CartItem $item, Cart $cart): Cart
    {
        dd("API : Ajout produit " . $item->getProduct()->getName());
    }

    public function remove(CartItem $item, Cart $cart): Cart
    {
        dd("API : Suppression produit " . $item->getProduct()->getName());
    }

    public function clearCart(string $identifier): void
    {
        dd("API : vider panier " . $identifier);
    }
}