<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    // Affiche tous les produits triés par nom, avec recherche (?q=...) et filtre catégorie (?category=...) optionnels
    #[Route('/products', name: 'products')]
    public function index(Request $request): Response
    {
        $query = trim((string) $request->query->get('q', ''));

        $categoryParam = $request->query->get('category');
        $categoryId = ($categoryParam !== null && $categoryParam !== '') ? (int) $categoryParam : null;

        if ($query !== '' || $categoryId !== null) {
            $products = $this->productRepository->search($query !== '' ? $query : null, $categoryId);
        } else {
            // ✅ Trié par nom ASC au lieu de findAll()
            $products = $this->productRepository->findBy([], ['name' => 'ASC']);
        }

        $categories = $this->categoryRepository->findAllCategories();

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'searchQuery' => $query,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
        ]);
    }

    // Affiche les produits par catégorie
    #[Route('/category/{id}', name: 'products_by_category')]
    public function byCategory(int $id): Response
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Cette catégorie n\'existe pas.');
        }

        // ✅ Trié par nom ASC
        $products = $this->productRepository->findBy(
            ['category' => $category],
            ['name' => 'ASC']
        );

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
            // ✅ Message d'erreur personnalisé
            throw $this->createNotFoundException('Ce produit n\'existe pas ou a été supprimé.');
        }

        return $this->render('product/product_details.html.twig', [
            'product' => $product,
        ]);
    }
}