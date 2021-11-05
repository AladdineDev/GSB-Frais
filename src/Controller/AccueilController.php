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
        if ($this->isGranted('ROLE_COMPTABLE')) {
            return $this->redirectToRoute('comptable');
        }
        if ($this->isGranted('ROLE_VISITEUR')) {
            return $this->redirectToRoute('visiteur');
        }
    }
}
