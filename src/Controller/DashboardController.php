<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\HistoryRepository;
use App\Repository\CategoryRepository;
use App\Repository\RepairRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard')]
final class DashboardController extends AbstractController
{

    #[Route(name: 'app_dashboard', methods: ['GET'])]
    public function dashboard(ProductRepository $productRepository, HistoryRepository $historyRepository, CategoryRepository $categoryRepository, RepairRepository $repairRepository): Response
    {
        // Renvoyer les valeurs récupérées dans les repository qui sont à afficher dans le dashboard
        return $this->render('dashboard.html.twig', [
            'productAllCount' => $productRepository->count(),
            'productBrokenCount' => $productRepository->countBroken(),
            
            'historyAllCount' => $historyRepository->count(),
            'historyOngoingCount' => $historyRepository->countOngoing(),
            'historyLateCount' => $historyRepository->countLate(),
            'historyBackCount' => $historyRepository->countBack(),

            'categoryAllCount' => $categoryRepository->count(),

            'repairAllCount' => $repairRepository->count(),
            'repairToDoCount' => $repairRepository->countToDo(),
            'repairToDoCost' => $repairRepository->sumCostToDo(),
            'repairLateCount' => $repairRepository->countLate(),
            'repairDoneCount' => $repairRepository->countDone()
        ]);
    }

}
