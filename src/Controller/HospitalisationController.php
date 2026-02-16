<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HospitalisationController extends AbstractController
{
    #[Route('/hospitalisation', name: 'app_hospitalisation')]
    public function index(): Response
    {
        return $this->render('hospitalisation/index.html.twig', [
            'controller_name' => 'HospitalisationController',
        ]);
    }
}
