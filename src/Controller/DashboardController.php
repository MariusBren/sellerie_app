<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\HistoryRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard')]
final class DashboardController extends AbstractController
{

    #[Route(name: 'app_dashboard', methods: ['GET'])]
    public function dashboard(ProductRepository $productRepository, HistoryRepository $historyRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('dashboard.html.twig', [
            'productAllCount' => $productRepository->count(),
            'productBrokenCount' => $productRepository->countBroken(),
            'historyAllCount' => $historyRepository->count(),
            'historyOngoingCount' => $historyRepository->countOngoing(),
            'historyLateCount' => $historyRepository->countLate(),
            'categoryAllCount' => $categoryRepository->count(),
        ]);
    }

}
