<?php

namespace App\Entity;

use App\Enum\StatutHospitalisation;
use App\Repository\HospitalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HospitalisationRepository::class)]
class Hospitalisation
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    #[ORM\JoinColumn(nullable: false)]
    private DossierMedical $dossierMedical;

    #[ORM\ManyToOne(inversedBy: 'hospitalisations')]
    #[ORM\JoinColumn(nullable: false)]
    private Utilisateur $medecinReferent;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $dateAdmission;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $dateSortie = null;

    #[ORM\Column(length: 255)]
    private string $motifAdmission;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $histoireMaladie = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $evolution = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $conclusion = null;

    #[ORM\Column(enumType: StatutHospitalisation::class)]
    private StatutHospitalisation $statut = StatutHospitalisation::EN_COURS;

    #[ORM\OneToOne(mappedBy: 'hospitalisation', targetEntity: ExamenClinique::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?ExamenClinique $examenClinique = null;

    #[ORM\OneToOne(mappedBy: 'hospitalisation', targetEntity: ExamenNeurologique::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?ExamenNeurologique $examenNeurologique = null;

    #[ORM\OneToMany(mappedBy: 'hospitalisation', targetEntity: ExamenComplementaire::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $examensComplementaires;

    #[ORM\OneToMany(mappedBy: 'hospitalisation', targetEntity: Antecedent::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $antecedents;

    #[ORM\OneToMany(mappedBy: 'hospitalisation', targetEntity: TraitementHospitalisation::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $traitements;

    public function __construct(
        DossierMedical $dossierMedical,
        Utilisateur $medecinReferent,
        string $motifAdmission
    ) {
        $this->dossierMedical = $dossierMedical;
        $this->medecinReferent = $medecinReferent;
        $this->motifAdmission = $motifAdmission;
        $this->dateAdmission = new \DateTimeImmutable();
        $this->statut = StatutHospitalisation::EN_COURS;
        $this->traitements = new ArrayCollection();
    }

    // --------------------
    // GETTERS
    // --------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossierMedical(): DossierMedical
    {
        return $this->dossierMedical;
    }

    public function getMedecinReferent(): Utilisateur
    {
        return $this->medecinReferent;
    }

    public function getDateAdmission(): \DateTimeImmutable
    {
        return $this->dateAdmission;
    }

    public function getDateSortie(): ?\DateTimeImmutable
    {
        return $this->dateSortie;
    }

    public function getMotifAdmission(): string
    {
        return $this->motifAdmission;
    }

    public function getHistoireMaladie(): ?string
    {
        return $this->histoireMaladie;
    }

    public function getEvolution(): ?string
    {
        return $this->evolution;
    }

    public function getConclusion(): ?string
    {
        return $this->conclusion;
    }

    public function getStatut(): StatutHospitalisation
    {
        return $this->statut;
    }

    /**
     * @return Collection<int, TraitementHospitalisation>
     */
    public function getTraitements(): Collection
    {
        return $this->traitements;
    }

    // --------------------
    // SETTERS contrôlés
    // --------------------

    public function setHistoireMaladie(?string $histoireMaladie): self
    {
        $this->histoireMaladie = $histoireMaladie;
        return $this;
    }

    public function setEvolution(?string $evolution): self
    {
        $this->evolution = $evolution;
        return $this;
    }

    public function setConclusion(?string $conclusion): self
    {
        $this->conclusion = $conclusion;
        return $this;
    }
}
