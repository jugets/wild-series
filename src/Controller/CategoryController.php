<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{categoryName}', methods: ['GET'], name: 'category_show')]
    public function show(CategoryRepository $categoryRepository, ProgramRepository $programRepository, string $categoryName): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);
        $programs = $programRepository->findBy(array('category' => $category), array('id' => 'DESC'), 3);

        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'category' => $category,
        ]);
    }
}
