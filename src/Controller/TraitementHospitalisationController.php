<?php

namespace App\Controller;

use App\Entity\TraitementHospitalisation;
use App\Form\TraitementHospitalisationType;
use App\Repository\TraitementHospitalisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/traitement/hospitalisation')]
final class TraitementHospitalisationController extends AbstractController
{
    #[Route(name: 'app_traitement_hospitalisation_index', methods: ['GET'])]
    public function index(TraitementHospitalisationRepository $traitementHospitalisationRepository): Response
    {
        return $this->render('traitement_hospitalisation/index.html.twig', [
            'traitement_hospitalisations' => $traitementHospitalisationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_traitement_hospitalisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $traitementHospitalisation = new TraitementHospitalisation();
        $form = $this->createForm(TraitementHospitalisationType::class, $traitementHospitalisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($traitementHospitalisation);
            $entityManager->flush();

            return $this->redirectToRoute('app_traitement_hospitalisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('traitement_hospitalisation/new.html.twig', [
            'traitement_hospitalisation' => $traitementHospitalisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_traitement_hospitalisation_show', methods: ['GET'])]
    public function show(TraitementHospitalisation $traitementHospitalisation): Response
    {
        return $this->render('traitement_hospitalisation/show.html.twig', [
            'traitement_hospitalisation' => $traitementHospitalisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_traitement_hospitalisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TraitementHospitalisation $traitementHospitalisation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TraitementHospitalisationType::class, $traitementHospitalisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_traitement_hospitalisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('traitement_hospitalisation/edit.html.twig', [
            'traitement_hospitalisation' => $traitementHospitalisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_traitement_hospitalisation_delete', methods: ['POST'])]
    public function delete(Request $request, TraitementHospitalisation $traitementHospitalisation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$traitementHospitalisation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($traitementHospitalisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_traitement_hospitalisation_index', [], Response::HTTP_SEE_OTHER);
    }
}
