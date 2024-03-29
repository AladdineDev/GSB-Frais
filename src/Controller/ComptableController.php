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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ComptableController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function index(): Response
    {
        return $this->render('comptable/index.html.twig', [
            'controller_name' => 'ComptableController',
        ]);
    }

    public function selectionnerFicheFrais(FichefraisRepository $ficheFraisRepository, Request $request): Response
    {
        $visiteurInstance = new Visiteur();
        $formVisiteur = $this->createForm(VisiteurType::class, $visiteurInstance);
        $formVisiteur->handleRequest($request);

        if ($formVisiteur->isSubmitted() && $formVisiteur->isValid()) {
            $visiteur = $formVisiteur->get('nom')->getData();
            $fichesFraisSaisies = $ficheFraisRepository->findFichesfraisSaisies($visiteur);

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
        $ligneFraisHorsForfaits = $em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($ficheFrais);
        $ligneFraisForfaits = $em->getRepository(Lignefraisforfait::class)->findByFichefrais($ficheFrais);
        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();
        $statuts = $em->getRepository(Statut::class)->findAll();

        $ficheFraisInstance = new Fichefrais();
        $formFicheFrais = $this->createForm(FichefraisType::class, $ficheFraisInstance);
        $formFicheFrais->handleRequest($request);

        if ($formFicheFrais->isSubmitted() && $formFicheFrais->isValid()) {
            $etatCloturee = $em->getRepository(Etat::class)->find('CL');
            if ($ficheFrais->getIdEtat() != $etatCloturee) {
                throw $this->createAccessDeniedException();
            }
            $i = 0;
            foreach ($formFicheFrais->getData()->getLignefraisforfaits() as $ligneFraisForfaitInput) {
                $ligneFraisForfaits[$i]->setQuantite($ligneFraisForfaitInput->getQuantite());
                $em->persist($ligneFraisForfaits[$i]);
                $i++;
            }
            $em->flush();
            $this->addFlash('ligneFraisForfaitsValidee', 'Les frais forfaitisés ont bien été actualisés.');

            return $this->redirectToRoute('administrer_fiche_frais', ['idFicheFrais' => $idFicheFrais]);
        }

        return $this->render('comptable/administrer_fiche_frais.html.twig', [
            'controller_name' => 'ComptableController',
            'formFicheFrais' => $formFicheFrais->createView(),
            'ficheFrais' => $ficheFrais,
            'ligneFraisForfaits' => $ligneFraisForfaits,
            'ligneFraisHorsForfaits' => $ligneFraisHorsForfaits,
            'fraisForfaits' => $fraisForfaits,
            'statuts' => $statuts
        ]);
    }

    public function modifierStatutFraisHorsForfait(Lignefraishorsforfait $ligneFraisHorsForfait, Request $request)
    {
        $ficheFrais = $ligneFraisHorsForfait->getIdFicheFrais();
        $ligneFraisHorsForfaits = $this->em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($ficheFrais);

        if ($this->isCsrfTokenValid('edit' . $ligneFraisHorsForfait->getId(), $request->request->get('modifier_statut_frais_hors_forfait_token'))) {
            $etatCloturee = $this->em->getRepository(Etat::class)->find('CL');
            if ($ligneFraisHorsForfait->getIdFicheFrais()->getIdEtat() != $etatCloturee) {
                throw $this->createAccessDeniedException();
            }
            $statutRefuse = $this->em->getRepository(Statut::class)->find('REF');
            $statutValide = $this->em->getRepository(Statut::class)->find('VAL');
            $requestToArray = $request->request->all();
            $confirmationStatut = array_pop($requestToArray);
            if ($confirmationStatut == 'VAL') {
                $ligneFraisHorsForfait->setIdStatut($statutValide);
                $this->addFlash('ligneFraisHorsForfaitValide', 'Le frais hors forfait a bien été validé.');
            } else {
                $ligneFraisHorsForfait->setIdStatut($statutRefuse);
                $this->addFlash('ligneFraisHorsForfaitRefuse', 'Le frais hors forfait a bien été refusé.');
            }
            $montantValide = 0;
            foreach ($ligneFraisHorsForfaits as $uneLigneFraisHorsForfait) {
                if ($uneLigneFraisHorsForfait->getIdStatut() == $statutValide) {
                    $montantValide += $uneLigneFraisHorsForfait->getMontant();
                }
            }
            $ficheFrais->setMontantValide($montantValide);
            $this->em->persist($ficheFrais);
            $this->em->persist($ligneFraisHorsForfait);
            $this->em->flush();
        }
        return $this->redirectToRoute('administrer_fiche_frais', ['idFicheFrais' => $ligneFraisHorsForfait->getIdficheFrais()->getId()]);
    }

    public function validerFicheFrais(Fichefrais $ficheFrais, Request $request)
    {
        if ($this->isCsrfTokenValid('validate' . $ficheFrais->getId(), $request->request->get('valider_fiche_frais_token'))) {
            $ligneFraisHorsForfaits = $this->em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($ficheFrais);
            foreach ($ligneFraisHorsForfaits as $ligneFraisHorsForfait) {
                if ($ligneFraisHorsForfait->getIdstatut()->getId() == 'ATT') {
                    $this->addFlash(
                        'ligneFraisHorsForfaitEnAttente',
                        'Tous les frais hors forfaits n\'ont pas été traités !
                         Veuillez traiter ceux toujours en attente.'
                    );
                    return $this->redirectToRoute('administrer_fiche_frais', ['idFicheFrais' => $ficheFrais->getId()]);
                }
            }
            $ligneFraisForfaits = $this->em->getRepository(Lignefraisforfait::class)->findByFichefrais($ficheFrais);
            foreach ($ligneFraisForfaits as $ligneFraisForfait) {
                $ficheFrais->setMontantValide(
                    $ficheFrais->getMontantValide() + $ligneFraisForfait->getQuantite() * $ligneFraisForfait->getFraisForfait()->getMontant()
                );
            }
            $etatValide = $this->em->getRepository(Etat::class)->find('VA');
            $ficheFrais->setIdEtat($etatValide);
            $ficheFrais->setDateModif(new \Datetime('now'));
            $this->em->persist($ficheFrais);
            $this->em->flush();
            $this->addFlash('ficheFraisValide', 'La fiche de frais a bien été validée.');
        }
        return $this->redirectToRoute('administrer_fiche_frais', ['idFicheFrais' => $ficheFrais->getId()]);
    }

    public function rembouserFicheFrais(Fichefrais $ficheFrais, Request $request)
    {
        if ($this->isCsrfTokenValid('refund' . $ficheFrais->getId(), $request->request->get('rembourser_fiche_frais_token'))) {
            $etatRembourse = $this->em->getRepository(Etat::class)->find('RB');
            $ficheFrais->setIdEtat($etatRembourse);
            $ficheFrais->setDateModif(new \Datetime('now'));
            $this->em->persist($ficheFrais);
            $this->em->flush();
            $this->addFlash('ficheFraisRembourse', 'Vous avez bien indiqué le remboursement de cette fiche de frais.');
        }
        return $this->redirectToRoute('administrer_fiche_frais', ['idFicheFrais' => $ficheFrais->getId()]);
    }
}
