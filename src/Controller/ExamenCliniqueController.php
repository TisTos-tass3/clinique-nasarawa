<?php

namespace App\Controller;

use App\Entity\ExamenClinique;
use App\Form\ExamenCliniqueType;
use App\Repository\ExamenCliniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/examen/clinique')]
final class ExamenCliniqueController extends AbstractController
{
    #[Route(name: 'app_examen_clinique_index', methods: ['GET'])]
    public function index(ExamenCliniqueRepository $examenCliniqueRepository): Response
    {
        return $this->render('examen_clinique/index.html.twig', [
            'examen_cliniques' => $examenCliniqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_examen_clinique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $examenClinique = new ExamenClinique();
        $form = $this->createForm(ExamenCliniqueType::class, $examenClinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($examenClinique);
            $entityManager->flush();

            return $this->redirectToRoute('app_examen_clinique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('examen_clinique/new.html.twig', [
            'examen_clinique' => $examenClinique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_clinique_show', methods: ['GET'])]
    public function show(ExamenClinique $examenClinique): Response
    {
        return $this->render('examen_clinique/show.html.twig', [
            'examen_clinique' => $examenClinique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_examen_clinique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ExamenClinique $examenClinique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExamenCliniqueType::class, $examenClinique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_examen_clinique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('examen_clinique/edit.html.twig', [
            'examen_clinique' => $examenClinique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_clinique_delete', methods: ['POST'])]
    public function delete(Request $request, ExamenClinique $examenClinique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenClinique->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($examenClinique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_examen_clinique_index', [], Response::HTTP_SEE_OTHER);
    }
}
