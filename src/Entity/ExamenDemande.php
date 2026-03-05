<?php

namespace App\Entity;

use App\Enum\StatutExamenDemande;
use App\Repository\ExamenDemandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenDemandeRepository::class)]
class ExamenDemande
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'examensDemandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Consultation $consultation = null;

    // Type standardisé ou libellé libre (tu peux remplacer par ton enum TypeExamenComplementaire)
    #[ORM\Column(length: 255)]
    private string $libelle = '';

    #[ORM\Column(nullable: true)]
    private ?bool $urgence = false;

    #[ORM\Column(enumType: StatutExamenDemande::class)]
    private StatutExamenDemande $statut = StatutExamenDemande::DEMANDE;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $prixUnitaire = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $resultatTexte = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $resultatSaisiLe = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Utilisateur $resultatSaisiPar = null;

    public function setResultatTexte(?string $resultatTexte): self
    {
        $this->resultatTexte = $resultatTexte;
        return $this;
    }

    public function getResultatTexte(): ?string
    {
        return $this->resultatTexte;
    }

    public function getResultatSaisiLe(): ?\DateTimeImmutable
    {
        return $this->resultatSaisiLe;
    }

    public function setResultatSaisiLe(?\DateTimeImmutable $resultatSaisiLe): static
    {
        $this->resultatSaisiLe = $resultatSaisiLe;

        return $this;
    }

    public function getPrixUnitaire(): ?string { return $this->prixUnitaire; }
    public function setPrixUnitaire(?string $prixUnitaire): self { $this->prixUnitaire = $prixUnitaire; return $this; }

    public function getId(): ?int { return $this->id; }

    public function getConsultation(): ?Consultation { return $this->consultation; }
    public function setConsultation(?Consultation $consultation): self { $this->consultation = $consultation; return $this; }

    public function getLibelle(): string { return $this->libelle; }
    public function setLibelle(string $libelle): self { $this->libelle = $libelle; return $this; }

    public function isUrgence(): ?bool { return $this->urgence; }
    public function setUrgence(?bool $urgence): self { $this->urgence = $urgence; return $this; }

    public function getStatut(): StatutExamenDemande { return $this->statut; }
    public function setStatut(StatutExamenDemande $statut): self { $this->statut = $statut; return $this; }

    public function getNote(): ?string { return $this->note; }
    public function setNote(?string $note): self { $this->note = $note; return $this; }
}
