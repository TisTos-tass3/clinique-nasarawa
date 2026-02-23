<?php

namespace App\Controller;

use App\Entity\ServiceMedical;
use App\Form\ServiceMedicalType;
use App\Repository\ServiceMedicalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/service/medical')]
final class ServiceMedicalController extends AbstractController
{
    #[Route(name: 'app_service_medical_index', methods: ['GET'])]
    public function index(ServiceMedicalRepository $serviceMedicalRepository): Response
    {
        return $this->render('service_medical/index.html.twig', [
            'service_medicals' => $serviceMedicalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_medical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serviceMedical = new ServiceMedical();
        $form = $this->createForm(ServiceMedicalType::class, $serviceMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceMedical);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_medical/new.html.twig', [
            'service_medical' => $serviceMedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_medical_show', methods: ['GET'])]
    public function show(ServiceMedical $serviceMedical): Response
    {
        return $this->render('service_medical/show.html.twig', [
            'service_medical' => $serviceMedical,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_medical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ServiceMedical $serviceMedical, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceMedicalType::class, $serviceMedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_service_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_medical/edit.html.twig', [
            'service_medical' => $serviceMedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_medical_delete', methods: ['POST'])]
    public function delete(Request $request, ServiceMedical $serviceMedical, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serviceMedical->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($serviceMedical);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_medical_index', [], Response::HTTP_SEE_OTHER);
    }
}
