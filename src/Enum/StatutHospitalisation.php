<?php
// src/Domain/Hospitalisation/Enum/StatutHospitalisation.php
namespace App\Enum;

enum StatutHospitalisation: string
{
    case EN_COURS = 'en_cours';
    case CLOTUREE = 'cloturee';
}