<?php

namespace App\Entity;


use App\Repository\PrescriptionLigneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrescriptionLigneRepository::class)]
#[ORM\HasLifecycleCallbacks]
class PrescriptionLigne
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lignes')]
    #[ORM\JoinColumn(nullable: false)]
    private Prescription $prescription;

    #[ORM\Column]
    private int $quantite;

    #[ORM\Column(length: 255)]
    private string $posologie;

    #[ORM\Column(nullable: true)]
    private ?int $duree = null;
}
