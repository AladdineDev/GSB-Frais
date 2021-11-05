<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class ConnexionListener
{
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $utilisateur = $event->getAuthenticationToken()->getUser();
        $this->flashBag->add(
            'connexionOk',
            'Bienvenue ' . $utilisateur->getNom() . ' ' . $utilisateur->getPrenom() . ' !
             Vous êtes maintenant connecté en tant que ' . $utilisateur->getTypeUtilisateur() . ' !'
        );
    }
}
