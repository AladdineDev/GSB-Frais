<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GererFraisController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('gerer_frais/index.html.twig', [
            'controller_name' => 'GererFraisController',
        ]);
    }

    public function gererFrais(): Response
    {
        return $this->render('gerer_frais/index.html.twig', [
            'controller_name' => 'GererFraisController',
        ]);
    }


}
