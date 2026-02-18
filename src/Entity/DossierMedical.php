<?php

namespace App\Entity;

use App\Repository\DossierMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossierMedicalRepository::class)]
#[ORM\HasLifecycleCallbacks]
class DossierMedical
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'dossierMedical')]
    #[ORM\JoinColumn(nullable: false)]
    private Patient $patient;

    #[ORM\Column(length: 50, unique: true)]
    private string $numeroDossier;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $observations = null;

    #[ORM\OneToMany(mappedBy: 'dossierMedical', targetEntity: Consultation::class)]
    private Collection $consultations;

    #[ORM\OneToMany(mappedBy: 'dossierMedical', targetEntity: Hospitalisation::class)]
    private Collection $hospitalisations;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
        $this->hospitalisations = new ArrayCollection();
    }

    
}