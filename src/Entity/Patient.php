<?php

namespace App\Entity;



use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Patient
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
     #[ORM\Column(length: 100)]
    private string $nom;

    #[ORM\Column(length: 100)]
    private string $prenom;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $dateNaissance;

    #[ORM\Column(length: 30)]
    private string $telephone;

    #[ORM\Column(length: 50, unique: true)]
    private string $code;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $groupeSanguin = null;
}
