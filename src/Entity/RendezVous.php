<?php

namespace App\Entity;

use App\Enum\StatutRendezVous;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
#[ORM\HasLifecycleCallbacks]
class RendezVous
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Patient $patient;

    #[ORM\ManyToOne(inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $medecin;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $dateHeure;

    #[ORM\Column(enumType: StatutRendezVous::class)]
    private StatutRendezVous $statut = StatutRendezVous::PLANIFIE;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif = null;

    #[ORM\OneToOne(mappedBy: 'rendezVous', targetEntity: Consultation::class)]
    private ?Consultation $consultation = null;
}
