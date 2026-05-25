<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Service\Handlers\CartHandler;
use App\Interface\CartInterface;
use App\Service\SessionCart;
use App\DTO\CartItemDTO;
use App\Mapper\CartItemMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    public function __construct(
        private CartHandler $handler,
        #[Autowire(service: SessionCart::class)]
        private CartInterface $cartStrategy
    ) {}

        #[Route('/cart', name: 'cart')]
        public function index(SessionCart $cartService): Response
        {
            $cart = $cartService->getCart('main_cart');

            return $this->render('cart/cart.html.twig', [
                'items' => $cart->getCartItems()
            ]);
        }

        #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
        public function add(
            int $id,
            Request $request,
            EntityManagerInterface $em
        ): Response {

            $product = $em->find(Product::class, $id);

            if (!$product) {
                throw $this->createNotFoundException('Produit inexistant.');
            }

            $quantity = (int) $request->request->get('quantity', 1);

            $dto = new CartItemDTO(
                $product->getId(),
                $quantity,
                $product->getPrice()
            );

            $mapper = new CartItemMapper();
            $cartItem = $mapper->toEntity($dto, $product);
            $cart = $this->cartStrategy->getCart('main_cart');

            $this->cartStrategy->add($cartItem, $cart);

            $this->addFlash('success', 'Produit ajouté !');

            return $this->redirectToRoute('cart');
            }
    #[Route('/cart/remove/{id}', name: 'cart_remove', methods: ['POST'])]
    public function remove(int $id, SessionCart $cartService): Response
    {
        $cart = $cartService->getCart('main_cart');

        foreach ($cart->getCartItems() as $item) {
            if ($item->getProduct()->getId() === $id) {
                $cartService->remove($item, $cart);
                break;
            }
        }

        return $this->redirectToRoute('cart');
    }
}