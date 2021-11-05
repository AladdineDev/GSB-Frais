<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ComptableController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('comptable/index.html.twig', [
            'controller_name' => 'ComptableController',
        ]);
    }

    public function validerFicheFrais(): Response
    {
        return $this->render('comptable/valider_fiche_frais.html.twig', [
            'controller_name' => 'ComptableController',
        ]);
    }

    public function suivreFicheFrais(): Response
    {
        return $this->render('comptable/suivre_fiche_frais.html.twig', [
            'controller_name' => 'ComptableController',
        ]);
    }
}
