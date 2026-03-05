<?php

namespace App\Enum;

enum StatutBonExamen: string
{
    case DEMANDE = 'demande';
    case EN_COURS = 'en_cours';         // le labo a commencé
    case PARTIEL = 'partiel';           // certains résultats OK, pas tous
    case RESULTATS_DISPONIBLES = 'resultats_disponibles';
    case ANNULE = 'annule';
}