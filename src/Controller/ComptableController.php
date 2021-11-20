<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Statut;
use App\Entity\Visiteur;
use App\Entity\Fichefrais;
use App\Form\VisiteurType;
use App\Entity\Fraisforfait;
use App\Form\FichefraisType;
use App\Entity\Lignefraisforfait;
use App\Entity\Lignefraishorsforfait;
use App\Repository\FichefraisRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ComptableController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('comptable/index.html.twig', [
            'controller_name' => 'ComptableController',
        ]);
    }

    public function selectionnerFicheFrais(Request $request, FichefraisRepository $fichefraisRepository): Response
    {
        $visiteurInstance = new Visiteur();
        $formVisiteur = $this->createForm(VisiteurType::class, $visiteurInstance);
        $formVisiteur->handleRequest($request);

        if ($formVisiteur->isSubmitted() && $formVisiteur->isValid()) {
            $visiteur = $formVisiteur->get('nom')->getData();
            $fichesFraisSaisies = $fichefraisRepository->findFichesfraisSaisies($visiteur);

            if ($fichesFraisSaisies) {
                return $this->render('comptable/selectionner_fiche_frais.html.twig', [
                    'controller_name' => 'VisiteurController',
                    'formVisiteur' => $formVisiteur->createView(),
                    'fichesFraisSaisies' => $fichesFraisSaisies,
                ]);
            }
        }
        return $this->render('comptable/selectionner_fiche_frais.html.twig', [
            'controller_name' => 'ComptableController',
            'formVisiteur' => $formVisiteur->createView()
        ]);
    }

    public function administrerFicheFrais(int $idFicheFrais, Request $request, EntityManagerInterface $em): Response
    {
        $ficheFrais = $em->getRepository(Fichefrais::class)->findFichefrais($idFicheFrais);
        $fraisHorsForfaits = $em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($ficheFrais);
        $ligneFraisForfaits = $em->getRepository(Lignefraisforfait::class)->findByFichefrais($ficheFrais);
        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();
        $statuts = $em->getRepository(Statut::class)->findAll();

        $ficheFraisInstance = new Fichefrais();
        $formFicheFrais = $this->createForm(FichefraisType::class, $ficheFraisInstance);
        $formFicheFrais->handleRequest($request);

        if ($formFicheFrais->isSubmitted() && $formFicheFrais->isValid()) {
            $i = 0;
            foreach ($formFicheFrais->getData()->getLignefraisforfaits() as $ligneFraisForfaitInput) {
                $ligneFraisForfaits[$i]->setQuantite($ligneFraisForfaitInput->getQuantite());
                $em->persist($ligneFraisForfaits[$i]);
                $i++;
            }
            $em->flush();

            return $this->redirectToRoute('administrer_fiche_frais', ['idFicheFrais' => $idFicheFrais]);
        }

        return $this->render('comptable/administrer_fiche_frais.html.twig', [
            'controller_name' => 'ComptableController',
            'formFicheFrais' => $formFicheFrais->createView(),
            'ficheFrais' => $ficheFrais,
            'ligneFraisForfaits' => $ligneFraisForfaits,
            'fraisHorsForfaits' => $fraisHorsForfaits,
            'fraisForfaits' => $fraisForfaits,
            'statuts' => $statuts
        ]);
    }

    public function modifierStatutFraisHorsForfait(Request $request, Lignefraishorsforfait $lignefraishorsforfait)
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('edit' . $lignefraishorsforfait->getId(), $request->request->get('modifier_statut_frais_hors_forfait_token'))) {
            $statutRefuse = $em->getRepository(Statut::class)->find('REF');
            $statutValide = $em->getRepository(Statut::class)->find('VAL');
            if ($request->request->get('nouveau_statut') == 'VAL') {
                $lignefraishorsforfait->setIdstatut($statutValide);
            } else {
                $lignefraishorsforfait->setIdstatut($statutRefuse);
            }
            $em->persist($lignefraishorsforfait);
            $em->flush();
        }
        return $this->redirectToRoute('administrer_fiche_frais', ['idFicheFrais' => $lignefraishorsforfait->getIdficheFrais()->getId()]);
    }

    public function validerFicheFrais(Request $request, Fichefrais $fichefrais)
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('validate' . $fichefrais->getId(), $request->request->get('valider_fiche_frais_token'))) {
            $fraisHorsForfaits = $em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($fichefrais);
            foreach ($fraisHorsForfaits as $fraisHorsForfait) {
                if ($fraisHorsForfait->getIdstatut()->getId() == 'ATT') {
                    $this->addFlash(
                        'fraisHorsForfaitEnAttente',
                        'Tous les frais hors forfaits n\'ont pas été traités !
                         Veuillez traiter ceux toujours en attente.'
                    );
                    return $this->redirectToRoute('administrer_fiche_frais', ['idFicheFrais' => $fichefrais->getId()]);
                }
            }
            $etatValide = $em->getRepository(Etat::class)->find('VA');
            $fichefrais->setIdetat($etatValide);
            $fichefrais->setDatemodif(new DateTime('now'));
            $em->persist($fichefrais);
            $em->flush();
            $this->addFlash('ficheFraisValide', 'La fiche de frais a bien été validée !');
        }
        return $this->redirectToRoute('administrer_fiche_frais', ['idFicheFrais' => $fichefrais->getId()]);
    }
}
