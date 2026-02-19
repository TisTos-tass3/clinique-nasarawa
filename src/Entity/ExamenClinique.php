<?php

namespace App\Entity;

use App\Repository\ExamenCliniqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenCliniqueRepository::class)]
class ExamenClinique
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'examenClinique')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Hospitalisation $hospitalisation;

    #[ORM\Column(nullable: true)]
    private ?string $tensionArterielle = null;

    #[ORM\Column(nullable: true)]
    private ?int $pouls = null;

    #[ORM\Column(nullable: true)]
    private ?float $temperature = null;

    #[ORM\Column(nullable: true)]
    private ?float $saturationOxygene = null;

    #[ORM\Column(nullable: true)]
    private ?int $frequenceRespiratoire = null;

    #[ORM\Column(nullable: true)]
    private ?float $poids = null;

    #[ORM\Column(nullable: true)]
    private ?float $taille = null;

    #[ORM\Column(nullable: true)]
    private ?float $imc = null;

    #[ORM\Column]
    private bool $deshydratation = false;

    #[ORM\Column]
    private bool $oedeme = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;
}
