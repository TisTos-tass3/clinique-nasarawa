<?php

namespace App\Controller;

use App\Entity\ExamenNeurologique;
use App\Form\ExamenNeurologiqueType;
use App\Repository\ExamenNeurologiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/examen/neurologique')]
final class ExamenNeurologiqueController extends AbstractController
{
    #[Route(name: 'app_examen_neurologique_index', methods: ['GET'])]
    public function index(ExamenNeurologiqueRepository $examenNeurologiqueRepository): Response
    {
        return $this->render('examen_neurologique/index.html.twig', [
            'examen_neurologiques' => $examenNeurologiqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_examen_neurologique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $examenNeurologique = new ExamenNeurologique();
        $form = $this->createForm(ExamenNeurologiqueType::class, $examenNeurologique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($examenNeurologique);
            $entityManager->flush();

            return $this->redirectToRoute('app_examen_neurologique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('examen_neurologique/new.html.twig', [
            'examen_neurologique' => $examenNeurologique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_neurologique_show', methods: ['GET'])]
    public function show(ExamenNeurologique $examenNeurologique): Response
    {
        return $this->render('examen_neurologique/show.html.twig', [
            'examen_neurologique' => $examenNeurologique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_examen_neurologique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ExamenNeurologique $examenNeurologique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExamenNeurologiqueType::class, $examenNeurologique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_examen_neurologique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('examen_neurologique/edit.html.twig', [
            'examen_neurologique' => $examenNeurologique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_examen_neurologique_delete', methods: ['POST'])]
    public function delete(Request $request, ExamenNeurologique $examenNeurologique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examenNeurologique->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($examenNeurologique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_examen_neurologique_index', [], Response::HTTP_SEE_OTHER);
    }
}
