<?php

namespace App\Entity;

use App\Enum\TypeExamenComplementaire;
use App\Repository\ExamenComplementaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenComplementaireRepository::class)]
class ExamenComplementaire
{
     use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'examensComplementaires')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Hospitalisation $hospitalisation;

    #[ORM\Column(enumType: TypeExamenComplementaire::class)]
    private TypeExamenComplementaire $type;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $resultat = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $dateExamen = null;
}
