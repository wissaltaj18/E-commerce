<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    // Affiche tous les produits si aucune catégorie n'est spécifiée
    #[Route('/products', name: 'products')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
           
        ]);
    }

    // Affiche les produits par catégorie
    #[Route('/category/{id}', name: 'products_by_category')]
    public function byCategory(int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $products = $this->productRepository->findBy([
            'category' => $category
        ]);

        return $this->render('product/products_by_category.html.twig', [
            'products' => $products,
            'category' => $category,
        ]);
    }

    // Détails d'un produit
    #[Route('/product/{id}', name: 'product_details')]
    public function details(int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('product/product_details.html.twig', [
            'product' => $product,
        ]);
    }
}