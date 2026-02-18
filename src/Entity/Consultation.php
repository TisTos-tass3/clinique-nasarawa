<?php

namespace App\Entity;


use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Consultation
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $medecin;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    #[ORM\JoinColumn(nullable: false)]
    private DossierMedical $dossierMedical;

    #[ORM\OneToOne(inversedBy: 'consultation')]
    #[ORM\JoinColumn(nullable: true)]
    private ?RendezVous $rendezVous = null;

    #[ORM\Column(nullable: true)]
    private ?float $poids = null;

    #[ORM\Column(nullable: true)]
    private ?float $taille = null;

    #[ORM\Column(nullable: true)]
    private ?float $temperature = null;

    #[ORM\Column(nullable: true)]
    private ?string $tensionArterielle = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $motifs = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $diagnostic = null;

    #[ORM\Column(nullable: true)]
    private ?int $frequenceCardiaque = null;

    #[ORM\OneToMany(mappedBy: 'consultation', targetEntity: Prescription::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $prescriptions;

    #[ORM\OneToOne(mappedBy: 'consultation', targetEntity: Facture::class)]
    private ?Facture $facture = null;

    public function __construct()
    {
        $this->prescriptions = new ArrayCollection();
    }
}