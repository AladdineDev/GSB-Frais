<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AccueilController extends AbstractController
{
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('connexion');
        }

        $rolesUtilisateur = $this->getUser()->getRoles();

        if (in_array('ROLE_COMPTABLE', $rolesUtilisateur)) {
            return $this->redirectToRoute('comptable');
        }
        if (in_array('ROLE_VISITEUR', $rolesUtilisateur)) {
            return $this->redirectToRoute('visiteur');
        }
    }
}
