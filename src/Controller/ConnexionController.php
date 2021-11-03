<?php

namespace App\Controller;

use PDO;
use PDOException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
                        if ($typeUtilisateur == 'Visiteur') {
                            $typeUtilisateur = 'Visiteur médical';
                        }
                        $session->set('typeUtilisateur', $typeUtilisateur);
                        $session = new Session();
                        $session->getFlashBag()->add('connexionOk', 'Bienvenue ' . $utilisateur['nom'] . " " . $utilisateur['prenom'] .
                            ' ! Vous êtes maintenant connecté en tant que ' . $typeUtilisateur . " !");
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
        $session->clear();
        $session->invalidate();
        return $this->render('connexion/index.html.twig');
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
