<?php

namespace App\Controller;

use App\Entity\Fichefrais;
use App\Repository\FicheFraisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class VisiteurController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('visiteur/index.html.twig', [
            'controller_name' => 'VisiteurController',
        ]);
    }

    public function saisirFicheFrais(FicheFraisRepository $ficheFraisRepository): Response
    {
        $fichesFrais = $ficheFraisRepository->findAll();

        return $this->render('visiteur/saisir_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'fichesFrais' => $fichesFrais
        ]);
    }

    public function consulterFicheFrais(): Response
    {


        return $this->render('visiteur/index.html.twig', [
            'controller_name' => 'VisiteurController',
        ]);
    }
}
