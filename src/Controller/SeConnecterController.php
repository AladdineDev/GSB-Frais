<?php

namespace App\Controller;

use PDO;
use PDOException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SeConnecterController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('se_connecter/index.html.twig', [
            'controller_name' => 'SeConnecterController',
        ]);
    }

    public function seConnecter(Request $request): Response
    {
        try {
            $bd = new PDO(
                'mysql:host=localhost;dbname=gsbFrais',
                'gsbAdmin',
                'azerty'
            ) ;
            
            
            $reponse = $bd->query('SELECT * FROM Visiteur');
            $utilisateurs = $reponse -> fetchall() ;
            foreach ($utilisateurs as $utilisateurs) {
                $login = $request->query->get('login');
                $mdp = $request->query->get('mdp');
            }

                
            unset($bd) ;
        } catch (PDOException $e) {
            echo "Error PDO" ;
            echo $e->getMessage();
        }
        
        return $this->render('gerer_frais/gestion_frais.html.twig', [
            'controller_name' => 'SeConnecterController',
            'utilisateurs' => $utilisateurs
        ]);
    }
}
