<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Fichefrais;
use App\Entity\Fraisforfait;
use App\Entity\Lignefraisforfait;
use App\Entity\Lignefraishorsforfait;
use App\Entity\Visiteur;
use App\Form\FichefraisType;
use App\Form\FraisforfaitType;
use App\Form\LignefraisforfaitType;
use App\Form\LignefraishorsforfaitType;
use App\Repository\FichefraisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class VisiteurController extends AbstractController
{
    public function index(Request $request): Response
    {
        return $this->render('visiteur/index.html.twig', [
            'controller_name' => 'VisiteurController',
        ]);
    }

    public function saisirFicheFrais(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $session->start();
        $idvisiteur = $session->get('utilisateur')['id'];

        $visiteur = $entityManager->getRepository(Visiteur::class)->find($idvisiteur);
        $fraisForfaits = $entityManager->getRepository(Fraisforfait::class)->findAll();
        $ficheFrais = $entityManager->getRepository(Fichefrais::class)->findFichefraisCourante($idvisiteur);
        $ligneFraisForfaits = $entityManager->getRepository(Lignefraisforfait::class)->findByFichefrais($idvisiteur);
        $fraisHorsForfaits = $entityManager->getRepository(Lignefraishorsforfait::class)->findByFichefrais($idvisiteur);

        $ficheFraisInstance = new Fichefrais();
        $formFichefrais = $this->createForm(FichefraisType::class, $ficheFraisInstance);
        $formFichefrais->handleRequest($request);

        $ligneFraisForfaitInstance = new Lignefraisforfait();
        $formLigneFraisForfait = $this->createForm(LignefraisforfaitType::class, $ligneFraisForfaitInstance);
        $formLigneFraisForfait->handleRequest($request);

        $fraisHorsForfaitInstance = new Lignefraishorsforfait();
        $formFraisHorsForfait = $this->createForm(LignefraishorsforfaitType::class, $fraisHorsForfaitInstance);
        $formFraisHorsForfait->handleRequest($request);

        $fraisForfaitInstance = new Fraisforfait();
        $formFraisForfait = $this->createForm(FraisforfaitType::class, $fraisForfaitInstance);
        $formFraisForfait->handleRequest($request);

        if (!$ficheFrais) {
            $ficheFrais = $this->creerFichefrais($ficheFrais, $visiteur);
        }

        if (!$ligneFraisForfaits) {
            $ligneFraisForfaits = $this->creerLignefraisforfaits($ligneFraisForfaits, $ficheFrais, $fraisForfaits);
        }

        if ($formFraisForfait->isSubmitted() && $formFraisForfait->isValid()) {
            $i = 0;
            foreach ($formFraisForfait->getData()->getLignefraisforfaits() as $ligneFraisForfaitInput) {
                $ligneFraisForfaits[$i]->setQuantite($ligneFraisForfaitInput->getQuantite());
                $entityManager->persist($ligneFraisForfaits[$i]);
                $i++;
            }
            $entityManager->flush();

            return $this->redirectToRoute('saisir_fiche_frais');
        }

        if ($formFraisHorsForfait->isSubmitted() && $formFraisHorsForfait->isValid()) {
            $fraisHorsForfaitInstance->setIdvisiteur($ficheFrais->getIdvisiteur()->getId());
            $fraisHorsForfaitInstance->setMois($ficheFrais->getMois());
            $fraisHorsForfaitInstance->setIdfichefrais($ficheFrais);
            $entityManager->persist($formFraisHorsForfait->getData());
            $entityManager->flush();

            return $this->redirectToRoute('saisir_fiche_frais');
        }

        return $this->render('visiteur/saisir_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'formFichefrais' => $formFichefrais->createView(),
            'formLigneFraisForfait' => $formLigneFraisForfait->createView(),
            'formFraisHorsForfait' => $formFraisHorsForfait->createView(),
            'formFraisForfait' => $formFraisForfait->createView(),
            'ficheFrais' => $ficheFrais,
            'ligneFraisForfaits' => $ligneFraisForfaits,
            'fraisHorsForfaits' => $fraisHorsForfaits,
            'fraisForfaits' => $fraisForfaits
        ]);
    }

    public function consulterFicheFrais(Request $request, FichefraisRepository $ficheFraisRepository): Response
    {
        $fichesFrais = $ficheFraisRepository->findAll();

        $ficheFraisInstance = new Fichefrais();
        $formFichefrais = $this->createForm(FichefraisType::class, $ficheFraisInstance);
        $formFichefrais->handleRequest($request);

        return $this->render('visiteur/consulter_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'formFichefrais' => $formFichefrais->createView(),
            'fichesFrais' => $fichesFrais
        ]);
    }

    private function creerLignefraisforfaits($ligneFraisForfaits, $ficheFrais, $fraisForfaits)
    {
        $entityManager = $this->getDoctrine()->getManager();
        for ($i = 0; $i < 4; $i++) {
            // dd($ligneFraisForfaits);
            array_push($ligneFraisForfaits, new Lignefraisforfait);
            $ligneFraisForfaits[$i]->setFicheFrais($ficheFrais);
            $ligneFraisForfaits[$i]->setFraisForfait($fraisForfaits[$i]);
            $ligneFraisForfaits[$i]->setQuantite(0);
            $entityManager->persist($ligneFraisForfaits[$i]);
        }
        $entityManager->flush();

        return $ligneFraisForfaits;
    }

    private function creerFichefrais($ficheFrais, $visiteur)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ficheFrais = new ficheFrais();
        $ficheFrais->setIdvisiteur($visiteur);
        $ficheFrais->setIdetat($entityManager->getRepository(Etat::class)->find('CR'));
        $entityManager->persist($ficheFrais);
        $entityManager->flush();

        return $ficheFrais;
    }

    public function supprimerFraisHorsForfait(Request $request, Lignefraishorsforfait $lignefraishorsforfait): Response
    {
        if ($this->isCsrfTokenValid('delete' . $lignefraishorsforfait->getId(), $request->request->get('supprimer_fraishorsforfait_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lignefraishorsforfait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('saisir_fiche_frais', [], Response::HTTP_SEE_OTHER);
    }
}
