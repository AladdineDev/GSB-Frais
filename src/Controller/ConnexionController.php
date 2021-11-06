<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController
{
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/connexion.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    public function deconnexion()
    {
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
