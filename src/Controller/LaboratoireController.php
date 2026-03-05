<?php

namespace App\Controller;

use App\Entity\BonExamen;
use App\Entity\BonExamenLigne;
use App\Entity\ExamenDemande;
use App\Enum\StatutBonExamen;
use App\Enum\StatutExamenDemande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/laboratoire')]
final class LaboratoireController extends AbstractController
{
/*     #[Route('/examens', name: 'app_laboratoire_examens', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $statut = $request->query->get('statut', StatutExamenDemande::DEMANDE->value);
        $q = trim((string) $request->query->get('q', ''));

        $qb = $em->getRepository(ExamenDemande::class)->createQueryBuilder('e')
            ->join('e.consultation', 'c')
            ->leftJoin('c.rendezVous', 'r')
            ->leftJoin('r.patient', 'p')
            ->addSelect('c', 'r', 'p')
            ->orderBy('e.id', 'DESC');

        // Filtre statut
        if (StatutExamenDemande::tryFrom($statut)) {
            $qb->andWhere('e.statut = :s')->setParameter('s', StatutExamenDemande::from($statut));
        }

        // Recherche simple
        if ($q !== '') {
            $qb->andWhere('LOWER(e.libelle) LIKE :q OR LOWER(p.nom) LIKE :q OR LOWER(p.prenom) LIKE :q')
               ->setParameter('q', '%'.mb_strtolower($q).'%');
        }

        $examens = $qb->getQuery()->getResult();

        return $this->render('laboratoire/examens/index.html.twig', [
            'examens' => $examens,
            'statut' => $statut,
            'q' => $q,
        ]);
    }
 */
    #[Route('/examens/{id}/prelever', name: 'app_laboratoire_examens_prelever', methods: ['POST'])]
    public function prelever(ExamenDemande $examen, EntityManagerInterface $em, Request $request): Response
    {
        if ($examen->getStatut() !== StatutExamenDemande::DEMANDE) {
            return $this->json(['success' => false, 'message' => 'Statut invalide.'], 422);
        }

        $examen->setStatut(StatutExamenDemande::PRELEVE);
        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/examens/{id}/resultat', name: 'app_laboratoire_examens_resultat', methods: ['GET', 'POST'])]
    public function resultat(ExamenDemande $examen, EntityManagerInterface $em, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $resultat = (string) $request->request->get('resultatTexte', '');
            $prix = $request->request->get('prixUnitaire'); // optionnel
            $prix = $prix !== null ? (string) $prix : null;

            $examen->setResultatTexte($resultat !== '' ? $resultat : null);
            $examen->setResultatSaisiLe(new \DateTimeImmutable());
            $examen->setResultatSaisiLe($this->getUser()); // si Utilisateur est ton user

            // Optionnel : prix pour facturation
            if ($prix !== null && $prix !== '') {
                $examen->setPrixUnitaire($prix);
            }

            $examen->setStatut(StatutExamenDemande::RESULTAT_RECU);

            $em->flush();

            return $this->json(['success' => true]);
        }

        return $this->render('laboratoire/examens/_modal_resultat.html.twig', [
            'examen' => $examen,
        ]);
    }

     #[Route('/bons', name: 'app_labo_bons', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $statut = (string) $request->query->get('statut', StatutBonExamen::DEMANDE->value);
        $q = trim((string) $request->query->get('q', ''));

        $qb = $em->getRepository(BonExamen::class)->createQueryBuilder('b')
            ->leftJoin('b.patient', 'p')->addSelect('p')
            ->leftJoin('b.medecin', 'm')->addSelect('m')
            ->orderBy('b.id', 'DESC');

        if (StatutBonExamen::tryFrom($statut)) {
            $qb->andWhere('b.statut = :s')->setParameter('s', StatutBonExamen::from($statut));
        }

        if ($q !== '') {
            $qb->andWhere('LOWER(p.nom) LIKE :q OR LOWER(p.prenom) LIKE :q OR LOWER(b.token) LIKE :q')
               ->setParameter('q', '%'.mb_strtolower($q).'%');
        }

        return $this->render('laboratoire/bons/index.html.twig', [
            'bons' => $qb->getQuery()->getResult(),
            'statut' => $statut,
            'q' => $q,
        ]);
    }

    #[Route('/ligne/{id}/modal', name: 'app_labo_ligne_modal', methods: ['GET'])]
    public function ligneModal(BonExamenLigne $ligne): Response
    {
        return $this->render('laboratoire/bons/_modal_ligne.html.twig', [
            'ligne' => $ligne,
            'bon' => $ligne->getBon(),
        ]);
    }

    #[Route('/ligne/{id}/save', name: 'app_labo_ligne_save', methods: ['POST'])]
    public function ligneSave(BonExamenLigne $ligne, Request $request, EntityManagerInterface $em): Response
    {
        $ligne->setResultatValeur($request->request->get('resultatValeur') ?: null);
        $ligne->setUnite($request->request->get('unite') ?: null);
        $ligne->setValeursNormales($request->request->get('valeursNormales') ?: null);
        $ligne->setResultatTexte($request->request->get('resultatTexte') ?: null);

        $prix = $request->request->get('prixUnitaire');
        $ligne->setPrixUnitaire(($prix !== null && $prix !== '') ? (string)$prix : null);

        $ligne->setResultatSaisiLe(new \DateTimeImmutable());
        $ligne->setResultatSaisiPar($this->getUser());

        $bon = $ligne->getBon();
        $bon->recomputeStatut();

        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/bon/{id}/print', name: 'app_labo_bon_print', methods: ['GET'])]
    public function print(BonExamen $bon): Response
    {
        return $this->render('laboratoire/bons/print.html.twig', ['bon' => $bon]);
    }

    #[Route('/bon/token/{token}/print', name: 'app_labo_bon_print_token', methods: ['GET'])]
    public function printByToken(string $token, EntityManagerInterface $em): Response
    {
        $bon = $em->getRepository(BonExamen::class)->findOneBy(['token' => $token]);
        if (!$bon) throw $this->createNotFoundException('Bon introuvable');

        return $this->render('laboratoire/bons/print.html.twig', ['bon' => $bon]);
    }
}