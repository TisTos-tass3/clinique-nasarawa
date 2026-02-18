<?php
// src/Domain/Core/Enum/StatutRendezVous.php
namespace App\Enum;

enum StatutRendezVous: string
{
    case PLANIFIE = 'planifie';
    case CONFIRME = 'confirme';
    case ANNULE = 'annule';
    case TERMINE = 'termine';
}