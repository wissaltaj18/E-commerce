<?php

namespace App\Mapper;

use App\DTO\CartItemDTO;
use App\Entity\CartItem;
use App\Entity\Product;

class CartItemMapper
{
    public static function toDTO(CartItem $item): CartItemDTO
    {
        return new CartItemDTO(
            $item->getProduct()->getId(),
            $item->getQuantity(),
            $item->getPrice()
        );
    }

    public static function toEntity(CartItemDTO $dto, Product $product): CartItem
    {
        $item = new CartItem();
        $item->setProduct($product);
        $item->setQuantity($dto->quantity);
        $item->setPrice($dto->price);

        return $item;
    }
}