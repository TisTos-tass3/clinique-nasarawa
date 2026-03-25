<?php

namespace App\Repository;

use App\Entity\Consultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Consultation>
 */
class ConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consultation::class);
    }

    public function searchByDossierCodeOrTelephone(?string $term): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.dossierMedical', 'dm')
            ->leftJoin('dm.patient', 'p')
            ->leftJoin('c.medecin', 'm')
            ->addSelect('dm', 'p', 'm');

        if ($term && trim($term) !== '') {
            $term = mb_strtolower(trim($term));

            $qb->andWhere(
                'LOWER(dm.numeroDossier) LIKE :term
                 OR LOWER(p.code) LIKE :term
                 OR LOWER(p.telephone) LIKE :term'
            )
            ->setParameter('term', '%' . $term . '%');
        }

        return $qb->orderBy('c.createdAt', 'DESC')
                  ->getQuery()
                  ->getResult();
    }

    public function searchVisibleForUser(?string $search, \App\Entity\Utilisateur $user): array
{
    $qb = $this->createQueryBuilder('c')
        ->leftJoin('c.dossierMedical', 'dm')
        ->leftJoin('dm.patient', 'p')
        ->leftJoin('c.medecin', 'm')
        ->addSelect('dm', 'p', 'm')
        ->orderBy('c.id', 'DESC');

    if ($search) {
        $qb->andWhere('dm.codeDossier LIKE :search OR p.telephone LIKE :search')
           ->setParameter('search', '%' . trim($search) . '%');
    }

    $roles = $user->getRoles();

    $isAdmin = in_array('ROLE_ADMIN', $roles, true);
    $isAccueil = in_array('ROLE_ACCUEIL', $roles, true);
    $isInfirmier = in_array('ROLE_INFIRMIER', $roles, true);
    $isMedecin = in_array('ROLE_MEDECIN', $roles, true);

    // Admin / Accueil / Infirmier voient tout
    if ($isAdmin || $isAccueil || $isInfirmier) {
        return $qb->getQuery()->getResult();
    }

    // Médecin : uniquement ses consultations
    if ($isMedecin) {
        $qb->andWhere('c.medecin = :medecin')
           ->setParameter('medecin', $user);

        return $qb->getQuery()->getResult();
    }

    // Par sécurité : aucun accès si rôle non prévu
    $qb->andWhere('1 = 0');

    return $qb->getQuery()->getResult();
}
}