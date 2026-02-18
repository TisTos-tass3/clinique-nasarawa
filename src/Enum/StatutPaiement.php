<?php
// src/Domain/Core/Enum/StatutPaiement.php
namespace App\Enum;

enum StatutPaiement: string
{
    case EN_ATTENTE = 'en_attente';
    case PAYE = 'paye';
    case ANNULE = 'annule';
}