<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Statut;
use App\Entity\Fichefrais;
use App\Entity\Fraisforfait;
use App\Form\FichefraisType;
use App\Entity\Lignefraisforfait;
use App\Entity\Lignefraishorsforfait;
use App\Form\LignefraishorsforfaitType;
use App\Repository\FichefraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class VisiteurController extends AbstractController

{
    public function index(): Response
    {
        return $this->render('visiteur/index.html.twig', [
            'controller_name' => 'VisiteurController',
        ]);
    }

    public function saisirFicheFrais(Request $request, EntityManagerInterface $em): Response
    {
        $visiteur = $this->getUser();

        $ficheFrais = $em->getRepository(Fichefrais::class)->findFichefraisCourante($visiteur);
        $fraisHorsForfaits = $em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($ficheFrais);
        $ficheFraisFuture = $em->getRepository(Fichefrais::class)->findFichefraisFuture($visiteur);
        $ligneFraisForfaits = $em->getRepository(Lignefraisforfait::class)->findByFichefrais($ficheFrais);
        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();

        if (!$ficheFrais) {
            $ficheFrais = $this->creerFichefrais($visiteur);
            $ligneFraisForfaits = $ficheFrais->getLignefraisforfaits();
        }

        $ficheFraisInstance = new Fichefrais();
        $formFicheFrais = $this->createForm(FichefraisType::class, $ficheFraisInstance);
        $formFicheFrais->handleRequest($request);

        $fraisHorsForfaitInstance = new Lignefraishorsforfait();
        $formFraisHorsForfait = $this->createForm(LignefraishorsforfaitType::class, $fraisHorsForfaitInstance);
        $formFraisHorsForfait->handleRequest($request);

        if ($formFicheFrais->isSubmitted() && $formFicheFrais->isValid()) {
            $i = 0;
            foreach ($formFicheFrais->getData()->getLignefraisforfaits() as $ligneFraisForfaitInput) {
                $ligneFraisForfaits[$i]->setQuantite($ligneFraisForfaitInput->getQuantite());
                $em->persist($ligneFraisForfaits[$i]);
                $i++;
            }
            $em->flush();
            $this->addFlash('ligneFraisForfaitsValidee', 'Les frais forfaitisés ont bien été actualisés.');

            return $this->redirectToRoute('saisir_fiche_frais');
        }

        if ($formFraisHorsForfait->isSubmitted() && $formFraisHorsForfait->isValid()) {
            $statutAttente = $em->getRepository(Statut::class)->find('ATT');
            $fraisHorsForfaitInstance->setIdstatut($statutAttente);
            $fraisHorsForfaitInstance->setIdvisiteur($ficheFrais->getIdvisiteur()->getId());
            $fraisHorsForfaitInstance->setIdfichefrais($ficheFrais);
            if ((new \DateTime('now'))->format('d') >= 10) {
                $fraisHorsForfaitInstance = $this->reporterFraisHorsForfait($fraisHorsForfaitInstance);
                $this->addFlash('fraisHorsForfaitReporte', 'Le frais hors forfait a été reporté au mois prochain.');
            } else {
                $fraisHorsForfaitInstance->setMois($ficheFrais->getMois());
                $this->addFlash('fraisHorsForfaitAjoute', 'Le frais hors forfait a bien été ajouté.');
            }
            $em->persist($formFraisHorsForfait->getData());
            $em->flush();

            return $this->redirectToRoute('saisir_fiche_frais');
        }

        return $this->render('visiteur/saisir_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'formFraisHorsForfait' => $formFraisHorsForfait->createView(),
            'formFicheFrais' => $formFicheFrais->createView(),
            'ficheFrais' => $ficheFrais,
            'ficheFraisFuture' => $ficheFraisFuture,
            'ligneFraisForfaits' => $ligneFraisForfaits,
            'fraisHorsForfaits' => $fraisHorsForfaits,
            'fraisForfaits' => $fraisForfaits
        ]);
    }

    public function consulterFichesFrais(FichefraisRepository $ficheFraisRepository): Response
    {
        $visiteur = $this->getUser();

        $fichesFrais = $ficheFraisRepository->findFichesfrais($visiteur);
        $ficheFraisCourante = $ficheFraisRepository->findFichefraisCourante($visiteur);

        return $this->render('visiteur/consulter_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'fichesFrais' => $fichesFrais,
            'ficheFraisCourante' => $ficheFraisCourante,
        ]);
    }

    public function consulterDetailFicheFrais(int $idFicheFrais, EntityManagerInterface $em, Request $request): Response
    {
        $visiteur = $this->getUser();

        $ficheFrais = $em->getRepository(Fichefrais::class)->findFichefrais($idFicheFrais);
        $fichesFrais = $em->getRepository(Fichefrais::class)->findFichesfrais($visiteur);
        $ficheFraisCourante = $em->getRepository(Fichefrais::class)->findFichefraisCourante($visiteur);
        $ficheFraisFuture = $em->getRepository(Fichefrais::class)->findFichefraisFuture($visiteur);

        if ($ficheFrais == $ficheFraisCourante || !in_array($ficheFrais, $fichesFrais)) {
            throw $this->createAccessDeniedException();
        }

        $fraisHorsForfaits = $em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($ficheFrais);
        $ligneFraisForfaits = $em->getRepository(Lignefraisforfait::class)->findByFichefrais($ficheFrais);
        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();

        $ficheFraisInstance = new Fichefrais();
        $formFicheFrais = $this->createForm(FichefraisType::class, $ficheFraisInstance);
        $formFicheFrais->handleRequest($request);

        $fraisHorsForfaitInstance = new Lignefraishorsforfait();
        $formFraisHorsForfait = $this->createForm(LignefraishorsforfaitType::class, $fraisHorsForfaitInstance);
        $formFraisHorsForfait->handleRequest($request);

        if ($formFicheFrais->isSubmitted() || $formFraisHorsForfait->isSubmitted()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('visiteur/consulter_detail_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'formFraisHorsForfait' => $formFraisHorsForfait->createView(),
            'formFicheFrais' => $formFicheFrais->createView(),
            'ficheFrais' => $ficheFrais,
            'ficheFraisFuture' => $ficheFraisFuture,
            'ligneFraisForfaits' => $ligneFraisForfaits,
            'fraisHorsForfaits' => $fraisHorsForfaits,
            'fraisForfaits' => $fraisForfaits
        ]);
    }

    public function supprimerFraisHorsForfait(Request $request, Lignefraishorsforfait $lignefraishorsforfait): Response
    {
        $em = $this->getDoctrine()->getManager();
        $visiteur = $this->getUser();
        $ficheFraisCourante = $em->getRepository(Fichefrais::class)->findFichefraisCourante($visiteur);
        $ficheFraisFuture = $em->getRepository(Fichefrais::class)->findFichefraisFuture($visiteur);

        if (
            $lignefraishorsforfait->getMois() != $ficheFraisCourante->getMois() &&
            $lignefraishorsforfait->getMois() != $ficheFraisFuture->getMois()
        ) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $lignefraishorsforfait->getId(), $request->request->get('supprimer_fraishorsforfait_token'))) {
            $em->remove($lignefraishorsforfait);
            $em->flush();
            $this->addFlash('fraisHorsForfaitSupprime', 'Le frais hors forfait a bien été supprimé.');
        }

        if ($request->attributes->get('lignefraishorsforfait')->getIdfichefrais() == $ficheFraisFuture) {
            return $this->redirectToRoute('consulter_detail_fiche_frais', [
                'idFicheFrais' => $ficheFraisFuture->getId()
            ]);
        }

        return $this->redirectToRoute('saisir_fiche_frais');
    }

    private function creerFichefrais($visiteur)
    {
        $em = $this->getDoctrine()->getManager();

        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();
        $derniereFicheFrais = $em->getRepository(Fichefrais::class)->findOneBy(array('idvisiteur' => $visiteur), array('mois' => 'DESC'));

        if ($derniereFicheFrais) {
            $etatCloturee = $em->getRepository(Etat::class)->find('CL');
            $derniereFicheFrais->setIdetat($etatCloturee);
        }

        $ficheFrais = new ficheFrais();
        $ficheFrais->setIdvisiteur($visiteur);
        $ficheFrais->setIdetat($em->getRepository(Etat::class)->find('CR'));
        $em->persist($ficheFrais);

        $i = 0;
        foreach ($ficheFrais->getLignefraisforfaits() as $ligneFraisForfait) {
            $ligneFraisForfait->setFicheFrais($ficheFrais);
            $ligneFraisForfait->setFraisForfait($fraisForfaits[$i]);
            $ligneFraisForfait->setQuantite(0);
            $em->persist($ligneFraisForfait);
            $i++;
        }
        $em->flush();

        return $ficheFrais;
    }

    private function reporterFraisHorsForfait($fraisHorsForfait)
    {
        $em = $this->getDoctrine()->getManager();

        $visiteur = $this->getUser();

        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();
        $ficheFrais = $em->getRepository(Fichefrais::class)->findFichefraisFuture($visiteur);
        // dd($ficheFrais);
        if (!$ficheFrais) {
            $ficheFrais = new ficheFrais();
        }

        $ficheFrais->setIdvisiteur($this->getUser());
        $ficheFrais->setIdetat($em->getRepository(Etat::class)->find('CR'));
        $ficheFrais->setMois((new \DateTime())->modify('+1 month'));
        $fraisHorsForfait->setMois((new \DateTime())->modify('+1 month'));
        $em->persist($ficheFrais);

        $i = 0;
        foreach ($ficheFrais->getLignefraisforfaits() as $ligneFraisForfait) {
            $ligneFraisForfait->setFicheFrais($ficheFrais);
            $ligneFraisForfait->setFraisForfait($fraisForfaits[$i]);
            $ligneFraisForfait->setQuantite(0);
            $em->persist($ligneFraisForfait);
            $i++;
        }

        $statutAttente = $em->getRepository(Statut::class)->find('ATT');
        $fraisHorsForfait->setIdstatut($statutAttente);
        $fraisHorsForfait->setIdvisiteur($ficheFrais->getIdvisiteur()->getId());
        $fraisHorsForfait->setIdfichefrais($ficheFrais);

        $fraisHorsForfait->setMois((new \DateTime())->modify('+1 month'));
        $em->persist($fraisHorsForfait);

        $em->flush();

        return $fraisHorsForfait;
    }
}
