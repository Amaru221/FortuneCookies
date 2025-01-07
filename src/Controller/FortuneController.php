<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\FortuneCookieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FortuneController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface): Response
    {

        $entityManagerInterface->getFilters()
            ->enable('fortuneCookie_discontinued')
            ->setParameter('discontinued', false);

        $searchTerm = $request->query->get('q');
        if($searchTerm){
            $categories = $categoryRepository->search($searchTerm);
        }else{
            $categories = $categoryRepository->findAllOrdered();
        }


        return $this->render('fortune/homepage.html.twig',[
            'categories' => $categories
        ]);
    }

    #[Route('/category/{id}', name: 'app_category_show')]
    public function showCategory(int $id, CategoryRepository $categoryRepository, FortuneCookieRepository $fortuneCookieRepository): Response
    {

        $category = $categoryRepository->findWithFortunesJoin($id);

        if(!$category){
            throw $this->createNotFoundException('Category not found');
        }
        $stats = $fortuneCookieRepository->countByNumberPrintedForCategory($category);


        return $this->render('fortune/showCategory.html.twig',[
            'category' => $category,
            'fortunesPrinted' => $stats->fortunesPrinted,
            'fortunesAverage' => $stats->fortunesAverage,
            'categoryName' => $stats->categoryName,

        ]);
    }
}
