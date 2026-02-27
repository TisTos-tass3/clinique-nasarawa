<?php

namespace App\Controller;

use App\Entity\PrescriptionLigne;
use App\Form\PrescriptionLigneType;
use App\Repository\PrescriptionLigneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prescription/ligne')]
final class PrescriptionLigneController extends AbstractController
{
    #[Route(name: 'app_prescription_ligne_index', methods: ['GET'])]
    public function index(PrescriptionLigneRepository $prescriptionLigneRepository): Response
    {
        return $this->render('prescription_ligne/index.html.twig', [
            'prescription_lignes' => $prescriptionLigneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prescription_ligne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prescriptionLigne = new PrescriptionLigne();
        $form = $this->createForm(PrescriptionLigneType::class, $prescriptionLigne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prescriptionLigne);
            $entityManager->flush();

            return $this->redirectToRoute('app_prescription_ligne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prescription_ligne/new.html.twig', [
            'prescription_ligne' => $prescriptionLigne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prescription_ligne_show', methods: ['GET'])]
    public function show(PrescriptionLigne $prescriptionLigne): Response
    {
        return $this->render('prescription_ligne/show.html.twig', [
            'prescription_ligne' => $prescriptionLigne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prescription_ligne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PrescriptionLigne $prescriptionLigne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrescriptionLigneType::class, $prescriptionLigne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prescription_ligne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prescription_ligne/edit.html.twig', [
            'prescription_ligne' => $prescriptionLigne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prescription_ligne_delete', methods: ['POST'])]
    public function delete(Request $request, PrescriptionLigne $prescriptionLigne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prescriptionLigne->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prescriptionLigne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prescription_ligne_index', [], Response::HTTP_SEE_OTHER);
    }
}
