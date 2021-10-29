<?php

namespace App\Controller;

use PDO;
use PDOException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConnexionController extends AbstractController
{


    public function index(): Response
    {
        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }

    public function seConnecter(Request $request): Response
    {
        try {
            $bd = new PDO(
                'mysql:host=localhost;dbname=gsbFrais',
                'gsbAdmin',
                'azerty'
            );

            $typeUtilisateurs = array('Comptable', 'Visiteur');

            foreach ($typeUtilisateurs as $typeUtilisateur) {
                $reponse = $bd->query('SELECT * FROM ' . $typeUtilisateur);
                $utilisateurs = $reponse->fetchall();
                foreach ($utilisateurs as $utilisateur) {
                    $login = $request->request->get('login');
                    $mdp = $request->request->get('mdp');

                    if ($utilisateur['login'] == $login &&  $utilisateur['mdp'] == $mdp) {
                        $connexionOk = true;
                        $session = $request->getSession();
                        // dd($session);
                        $session->set('connexionOk', $connexionOk);
                        $session->set('utilisateur', $utilisateur);
                        $session->set('typeUtilisateur', $typeUtilisateur);
                        // dd($session);
                        if ($typeUtilisateur == "Comptable") {
                            return $this->redirectToRoute('comptable');
                        } else {
                            return $this->redirectToRoute('visiteur');
                        }
                    }
                }
            }
            $request->attributes->set('echec', true);
            unset($bd);
        } catch (PDOException $e) {
            echo "Error PDO";
            echo $e->getMessage();
        }
        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }

    public function seDeconnecter(Request $request): Response
    {
        $session = $request->getSession();
        $session->invalidate();
        return $this->render('connexion/index.html.twig');
    }
}
