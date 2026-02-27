<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    #[Route('/patient', name: 'patient_index')]
    public function index(): Response
    {
        return $this->render('patient/index.html.twig');
    }

    #[Route('/patient/new', name: 'patient_new')]
    public function new(): Response
    {
        return $this->render('patient/new.html.twig');
    }

    #[Route('/patient/show', name: 'patient_show')]
    public function show(): Response
    {
        return $this->render('patient/show.html.twig');
    }
}