<?php

namespace App\Entity;

use App\Enum\TypeAntecedent;
use App\Repository\AntecedentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AntecedentRepository::class)]
class Antecedent
{
     use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'antecedents')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Hospitalisation $hospitalisation;

    #[ORM\Column(enumType: TypeAntecedent::class)]
    private TypeAntecedent $type;

    #[ORM\Column(type: 'text')]
    private string $description;
}
