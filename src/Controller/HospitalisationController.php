<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HospitalisationController extends AbstractController
{
   #[Route('/hospitalisation', name: 'app_hospitalisation')]
    public function index(): Response
    {
       
        return $this->render('hospitalisation/index.html.twig');
    }

    #[Route('/hospitalisation/new', name: 'hospitalisation_new')]
    public function new(): Response
    {
        return $this->render('hospitalisation/new.html.twig');
    }

    #[Route('/hospitalisation/show', name: 'hospitalisation_show')]
    public function show(): Response
    {
        return $this->render('hospitalisation/show.html.twig');
    }
}