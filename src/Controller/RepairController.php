<?php

namespace App\Controller;

use App\Entity\Repair;
use App\Form\RepairType;
use App\Form\FinishRepairType;
use App\Repository\RepairRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/repair')]
final class RepairController extends AbstractController
{
    #[Route(name: 'app_repair_index', methods: ['GET'])]
    public function index(RepairRepository $repairRepository): Response
    {
        return $this->render('repair/index.html.twig', [
            'repairs' => $repairRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_repair_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repair = new Repair();
        $form = $this->createForm(RepairType::class, $repair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($repair);
            $entityManager->flush();

            return $this->redirectToRoute('app_repair_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repair/new.html.twig', [
            'repair' => $repair,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_repair_show', methods: ['GET'])]
    public function show(Repair $repair): Response
    {
        return $this->render('repair/show.html.twig', [
            'repair' => $repair,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_repair_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repair $repair, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RepairType::class, $repair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_repair_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repair/edit.html.twig', [
            'repair' => $repair,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_repair_delete', methods: ['POST'])]
    public function delete(Request $request, Repair $repair, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repair->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($repair);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_repair_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/finish', name: 'app_repair_finish', methods: ['GET', 'POST'])]
    public function return(Request $request, Repair $repair, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'historique n'ai pas déjà été retourné
        if ($repair->isDone()) {
            $this->addFlash('error', 'This repair has already been done.');
            return $this->redirectToRoute('app_repair_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(FinishRepairType::class, $repair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $repair->getProduct();

            // Définir la réparation comme terminée
            $repair->setDone(true);

            // MAJ la condition du produit
            $newConditionState = $form->get('condition_state')->getData();
            if ($newConditionState) {
                $product->setConditionState($newConditionState);
            }

            $entityManager->persist($repair);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_repair_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendre la vue avec le formulaire
        return $this->render('repair/finish.html.twig', [
            'repair' => $repair,
            'form' => $form,
        ]);
    }
}
