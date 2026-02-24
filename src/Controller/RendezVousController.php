<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\RendezVous;
use App\Enum\StatutConsultation;
use App\Enum\StatutRendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rendez/vous')]
final class RendezVousController extends AbstractController
{
   #[Route(name: 'app_rendez_vous_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        RendezVousRepository $rendezVousRepository,
        EntityManagerInterface $em
    ): Response {
        $rv = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

    // consultation non obligatoire à la création
    $rv->setConsultation(null);

    // statut par défaut si vide
    if (!$rv->getStatut()) {
        $rv->setStatut(StatutRendezVous::EN_ATTENTE);
    }

    $em->persist($rv);
    $em->flush();

    return $this->redirectToRoute('app_rendez_vous_index');
}

        return $this->render('rendez_vous/index.html.twig', [
            'rendezVous' => $rendezVousRepository->findAll(),  
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVous, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => true]);
            }
            return $this->redirectToRoute('app_rendez_vous_index');
        }

        // AJAX: renvoyer uniquement le contenu du form (pour la modale)
        if ($request->isXmlHttpRequest()) {
            return $this->render('rendez_vous/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        // Hors AJAX: page normale
        return $this->render('rendez_vous/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rendezVou);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/start-consultation', name: 'app_rendez_vous_start_consultation', methods: ['POST'])]
    public function startConsultation(
        Request $request,
        RendezVous $rendezVous,
        EntityManagerInterface $em
    ): Response {
        // 1) CSRF
        if (!$this->isCsrfTokenValid('start_consultation_' . $rendezVous->getId(), (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('CSRF token invalide.');
        }

        // 2) Empêcher double création
        if (null !== $rendezVous->getConsultation()) {
            $this->addFlash('info', 'Une consultation existe déjà pour ce rendez-vous.');
            return $this->redirectToRoute('app_consultation_show', ['id' => $rendezVous->getConsultation()->getId()]);
        }

        // 3) Statut RDV autorisé
        $statut = $rendezVous->getStatut(); // enum
        $allowed = [
            StatutRendezVous::PLANIFIE,
            StatutRendezVous::CONFIRME,

        ];
        if (!\in_array($statut, $allowed, true)) {
            $this->addFlash('warning', 'Le rendez-vous doit être PLANIFIE ou CONFIRME pour démarrer la consultation.');
            return $this->redirectToRoute('app_rendez_vous_index');
        }

        // 4) Données requises
        if (null === $rendezVous->getMedecin()) {
            $this->addFlash('danger', 'Aucun médecin n’est associé à ce rendez-vous.');
            return $this->redirectToRoute('app_rendez_vous_index');
        }
        if (null === $rendezVous->getPatient()) {
            $this->addFlash('danger', 'Aucun patient n’est associé à ce rendez-vous.');
            return $this->redirectToRoute('app_rendez_vous_index');
        }

        // 5) Récupérer/Créer DossierMedical (selon ton choix métier)
        // Option A (souvent recommandé): le dossier est créé à la création du patient (workflow déjà discuté)
        $dossier = $rendezVous->getPatient()->getDossierMedical();
        if (null === $dossier) {
            $this->addFlash('danger', 'Le patient n’a pas de dossier médical. Créez-le avant de démarrer la consultation.');
            return $this->redirectToRoute('app_rendez_vous_index');
        }

        // 6) Créer Consultation
        $consultation = new Consultation();
        $consultation->setRendezVous($rendezVous);
        $consultation->setMedecin($rendezVous->getMedecin());
        $consultation->setDossierMedical($dossier);
        $consultation->setStatut(StatutConsultation::EN_COURS); // ou BROUILLON

        // 7) Mettre à jour le RDV (optionnel mais cohérent)
        //$rendezVous->setStatut(StatutRendezVous::EN_COURS);

        $em->persist($consultation);
        $em->flush();

        $this->addFlash('success', 'Consultation démarrée.');

        // 8) Rediriger vers la fiche consultation
        return $this->redirectToRoute('app_consultation_index');
    }
}
