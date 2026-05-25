<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoryRepository;

final class CategoriesController extends AbstractController
{
    private CategoryRepository $categoryRepository;

   
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/categories', name: 'categories')]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAllCategories();

        return $this->render('categories/browse_categories.html.twig', [
            'categories' => $categories,
        ]);
    }
}