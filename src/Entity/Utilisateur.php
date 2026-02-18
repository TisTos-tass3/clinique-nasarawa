<?php

namespace App\Entity;


use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Utilisateur
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $nom;

    #[ORM\Column(length: 100)]
    private string $prenom;

    #[ORM\Column(length: 180, unique: true)]
    private string $username;

    #[ORM\Column]
    private string $hashPassword;

    #[ORM\Column]
    private bool $statut = true;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ServiceMedical $serviceMedical;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: RendezVous::class)]
    private Collection $rendezVous;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Consultation::class)]
    private Collection $consultations;

    #[ORM\OneToMany(mappedBy: 'medecinReferent', targetEntity: Hospitalisation::class)]
    private Collection $hospitalisations;

    public function __construct()
    {
        $this->rendezVous = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->hospitalisations = new ArrayCollection();
    }
    public function getId(): ?int { return $this->id; }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }

    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): self { $this->username = $username; return $this; }


    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_values(array_unique($roles));
    }
    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }

    public function eraseCredentials(): void {}

    public function isActif(): bool { return $this->statut; }
    public function setStatut(bool $statut): self { $this->statut = $statut; return $this; }

    public function getServiceMedical(): ServiceMedical { return $this->serviceMedical; }
    public function setServiceMedical(ServiceMedical $serviceMedical): self { $this->serviceMedical = $serviceMedical; return $this; }
}
