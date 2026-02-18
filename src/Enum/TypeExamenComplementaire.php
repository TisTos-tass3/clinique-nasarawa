<?php
// src/Domain/Hospitalisation/Enum/TypeExamenComplementaire.php
namespace App\Enum;

enum TypeExamenComplementaire: string
{
    case SCANNER = 'scanner';
    case ANGIO = 'angio';
    case IRM = 'irm';
    case ECG = 'ecg';
    case HOLTER = 'holter';
    case ETT = 'ett';
    case BIOLOGIE = 'biologie';
    case AUTRE = 'autre';
}
