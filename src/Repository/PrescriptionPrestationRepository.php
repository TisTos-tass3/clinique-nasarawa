<?php

namespace App\Repository;

use App\Entity\Consultation;
use App\Entity\PrescriptionPrestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrescriptionPrestation>
 */
class PrescriptionPrestationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrescriptionPrestation::class);
    }

    /**
     * @return PrescriptionPrestation[]
     */
    public function findByConsultation(Consultation $consultation): array
    {
        return $this->createQueryBuilder('pp')
            ->leftJoin('pp.tarifPrestation', 'tp')->addSelect('tp')
            ->andWhere('pp.consultation = :consultation')
            ->setParameter('consultation', $consultation)
            ->orderBy('pp.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PrescriptionPrestation[]
     */
    public function findAFacturerPourConsultation(Consultation $consultation): array
    {
        return $this->createQueryBuilder('pp')
            ->leftJoin('pp.tarifPrestation', 'tp')->addSelect('tp')
            ->andWhere('pp.consultation = :consultation')
            ->andWhere('pp.aFacturer = :aFacturer')
            ->setParameter('consultation', $consultation)
            ->setParameter('aFacturer', true)
            ->orderBy('pp.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return PrescriptionPrestation[]
     */
    public function findNonFacturees(): array
    {
        return $this->createQueryBuilder('pp')
            ->leftJoin('pp.tarifPrestation', 'tp')->addSelect('tp')
            ->andWhere('pp.aFacturer = :aFacturer')
            ->andWhere('pp.statut = :statut')
            ->setParameter('aFacturer', true)
            ->setParameter('statut', 'prescrit')
            ->orderBy('pp.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}