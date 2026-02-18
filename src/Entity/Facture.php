<?php

namespace App\Entity;

use App\Enum\ModePaiement;
use App\Enum\StatutPaiement;
use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Facture
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'facture')]
    #[ORM\JoinColumn(nullable: false)]
    private Consultation $consultation;

    #[ORM\Column]
    private float $montant;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $dateEmission;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $datePaiement = null;

    #[ORM\Column(enumType: StatutPaiement::class)]
    private StatutPaiement $statutPaiement = StatutPaiement::EN_ATTENTE;

    #[ORM\Column(enumType: ModePaiement::class, nullable: true)]
    private ?ModePaiement $modePaiement = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }
}
