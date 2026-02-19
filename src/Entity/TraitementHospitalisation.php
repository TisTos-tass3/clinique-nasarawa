<?php

namespace App\Entity;

use App\Repository\TraitementHospitalisationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraitementHospitalisationRepository::class)]
class TraitementHospitalisation
{
     use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'traitements')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Hospitalisation $hospitalisation;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $dateFin = null;
}
