<?php

namespace App\Controller;

use App\Entity\History;
use App\Entity\Repair;
use App\Form\HistoryType;
use App\Form\ReturnHistoryType;
use App\Repository\HistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/history')]
final class HistoryController extends AbstractController
{
    #[Route(name: 'app_history_index', methods: ['GET'])]
    public function index(HistoryRepository $historyRepository): Response
    {
        return $this->render('history/index.html.twig', [
            'histories' => $historyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_history_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $history = new History();
        $form = $this->createForm(HistoryType::class, $history);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($history);
            $entityManager->flush();

            return $this->redirectToRoute('app_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('history/new.html.twig', [
            'history' => $history,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_history_show', methods: ['GET'])]
    public function show(History $history): Response
    {
        return $this->render('history/show.html.twig', [
            'history' => $history,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_history_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, History $history, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HistoryType::class, $history);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('history/edit.html.twig', [
            'history' => $history,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_history_delete', methods: ['POST'])]
    public function delete(Request $request, History $history, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$history->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($history);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_history_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/return', name: 'app_history_return', methods: ['GET', 'POST'])]
    public function return(Request $request, History $history, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'historique n'ai pas déjà été retourné
        if ($history->isBack()) {
            $this->addFlash('error', 'This history has already been returned.');
            return $this->redirectToRoute('app_history_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(ReturnHistoryType::class, $history);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $history->getProduct();

            // Définir le prêt comme retourné
            $history->setBack(true);

            // MAJ la condition du produit
            $newConditionState = $form->get('condition_state')->getData();
            if ($newConditionState) {
                $product->setConditionState($newConditionState);
            }

            // Vérifier si la case de génération de réparation automatique a été cochée
            if ($form->get('create_repair')->getData()) {
                // Créer une réparation générique
                $repair = new Repair();
                $repair->setProduct($history->getProduct());
                $repair->setDescription('Maintenance de retour');
                $repair->setCost(100);
                $repair->setDate((new \DateTime())->modify('+1 week'));
                $repair->setDone(false);
    
                $entityManager->persist($repair);
            }

            $entityManager->persist($history);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_history_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendre la vue avec le formulaire
        return $this->render('history/return.html.twig', [
            'history' => $history,
            'form' => $form,
        ]);
    }
}
