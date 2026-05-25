<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Interface\CartInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionCart implements CartInterface
{
    public function __construct(
        private RequestStack $requestStack
    ) {}

    public function getCart(string $identifier): Cart
    {
        $session = $this->requestStack->getSession();

        $cart = $session->get($identifier);

        if (!$cart instanceof Cart) {
            $cart = new Cart();
            $cart->setId($identifier);
        }

        return $cart;
    }

    public function add(CartItem $item, Cart $cart): Cart
    {
        $session = $this->requestStack->getSession();

        $existingCart = $this->getCart('main_cart');

        $existingCart->addCartItem($item);

        $session->set('main_cart', $existingCart);

        return $existingCart;
    }
    public function remove(CartItem $item, Cart $cart): Cart
    {
        $session = $this->requestStack->getSession();

        foreach ($cart->getCartItems() as $existingItem) {
            if ($existingItem->getProduct()->getId() === $item->getProduct()->getId()) {
                $cart->removeCartItem($existingItem);
                break;
            }
        }

        $session->set('main_cart', $cart);

        return $cart;
    }

    public function clearCart(string $identifier): void
    {
        $this->requestStack->getSession()->remove($identifier);
    }
}