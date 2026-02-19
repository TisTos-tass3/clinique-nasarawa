<?php

namespace App\Entity;

use App\Repository\ExamenNeurologiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenNeurologiqueRepository::class)]
class ExamenNeurologique
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'examenNeurologique')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Hospitalisation $hospitalisation;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $conscience = null;

    #[ORM\Column(nullable: true)]
    private ?string $tonusMusculaire = null;

    #[ORM\Column(nullable: true)]
    private ?int $forceMembreSuperieurD = null;

    #[ORM\Column(nullable: true)]
    private ?int $forceMembreSuperieurG = null;

    #[ORM\Column(nullable: true)]
    private ?int $forceMembreInferieurD = null;

    #[ORM\Column(nullable: true)]
    private ?int $forceMembreInferieurG = null;

    #[ORM\Column]
    private bool $babinski = false;

    #[ORM\Column]
    private bool $grasping = false;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $aphasieType = null;

    #[ORM\Column]
    private bool $agnosie = false;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $apraxieType = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $troubleSphincteriens = null;

    #[ORM\Column]
    private bool $raideurNuque = false;

    #[ORM\Column]
    private bool $brudzinski = false;

    #[ORM\Column]
    private bool $kernig = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;
}
