<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class DeconnexionListener
{
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function onLogout(LogoutEvent $event): void
    {
        $this->flashBag->add('deconnexionOk', 'Vous avez bien été déconnecté.');
    }
}
