<?php

namespace App\Controller;

use App\Entity\ExamenComplementaire;
use App\Form\ExamenComplementaireType;
use App\Repository\ExamenComplementaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/examen/complementaire')]
final class ExamenComplementaireController extends AbstractController
{
    #[Route(name: 'app_examen_complementaire_index', methods: ['GET'])]
    public function index(ExamenComplementaireRepository $examenComplementaireRepository): Response
    {
        return $this->render('examen_complementaire/index.html.twig', [
            'examen_complementaires' => $examenComplementaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_examen_complementaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $examenComplementaire = new ExamenComplementaire();
        $form = $this->createForm(ExamenComplementaireType::class, $examenComplementaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($examenComplementaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_examen_complementaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('examen_complementaire/new.html.twig', [
            'examen_complementaire' => $examenComplementaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_complementaire_show', methods: ['GET'])]
    public function show(ExamenComplementaire $examenComplementaire): Response
    {
        return $this->render('examen_complementaire/show.html.twig', [
            'examen_complementaire' => $examenComplementaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_examen_complementaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ExamenComplementaire $examenComplementaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExamenComplementaireType::class, $examenComplementaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_examen_complementaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('examen_complementaire/edit.html.twig', [
            'examen_complementaire' => $examenComplementaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_complementaire_delete', methods: ['POST'])]
    public function delete(Request $request, ExamenComplementaire $examenComplementaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenComplementaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($examenComplementaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_examen_complementaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
