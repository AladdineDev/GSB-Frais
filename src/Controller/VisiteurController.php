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
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
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
        $ligneFraisHorsForfaits = $em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($ficheFrais);
        $ficheFraisProchaine = $em->getRepository(Fichefrais::class)->findFichefraisProchaine($visiteur);
        $ligneFraisForfaits = $em->getRepository(Lignefraisforfait::class)->findByFichefrais($ficheFrais);
        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();

        if (!$ficheFrais) {
            $ficheFrais = $this->creerFichefrais($visiteur);
            $ligneFraisForfaits = $ficheFrais->getLignefraisforfaits();
        }

        $ficheFraisInstance = new Fichefrais();
        $formFicheFrais = $this->createForm(FichefraisType::class, $ficheFraisInstance);
        $formFicheFrais->handleRequest($request);

        $ligneFraisHorsForfaitInstance = new Lignefraishorsforfait();
        $formLigneFraisHorsForfait = $this->createForm(LignefraishorsforfaitType::class, $ligneFraisHorsForfaitInstance);
        $formLigneFraisHorsForfait->handleRequest($request);

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

        if ($formLigneFraisHorsForfait->isSubmitted() && $formLigneFraisHorsForfait->isValid()) {
            $statutAttente = $em->getRepository(Statut::class)->find('ATT');
            $ligneFraisHorsForfaitInstance->setIdStatut($statutAttente);
            $ligneFraisHorsForfaitInstance->setIdVisiteur($ficheFrais->getIdVisiteur()->getId());
            $ligneFraisHorsForfaitInstance->setIdFicheFrais($ficheFrais);
            if ((new \DateTime('now'))->format('d') >= 10) {
                $ligneFraisHorsForfaitInstance = $this->reporterLigneFraisHorsForfait($ligneFraisHorsForfaitInstance);
                $this->addFlash('ligneFraisHorsForfaitReporte', 'Le frais hors forfait a été reporté au mois prochain.');
            } else {
                $ligneFraisHorsForfaitInstance->setMois($ficheFrais->getMois());
                $this->addFlash('ligneFraisHorsForfaitAjoute', 'Le frais hors forfait a bien été ajouté.');
            }
            $em->persist($formLigneFraisHorsForfait->getData());
            $em->flush();

            return $this->redirectToRoute('saisir_fiche_frais');
        }

        return $this->render('visiteur/saisir_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'formLigneFraisHorsForfait' => $formLigneFraisHorsForfait->createView(),
            'formFicheFrais' => $formFicheFrais->createView(),
            'ficheFrais' => $ficheFrais,
            'ficheFraisProchaine' => $ficheFraisProchaine,
            'ligneFraisForfaits' => $ligneFraisForfaits,
            'ligneFraisHorsForfaits' => $ligneFraisHorsForfaits,
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

    public function consulterDetailFicheFrais(int $idFicheFrais, Request $request, EntityManagerInterface $em): Response
    {
        $visiteur = $this->getUser();

        $ficheFrais = $em->getRepository(Fichefrais::class)->findFichefrais($idFicheFrais);
        $fichesFrais = $em->getRepository(Fichefrais::class)->findFichesfrais($visiteur);
        $ficheFraisCourante = $em->getRepository(Fichefrais::class)->findFichefraisCourante($visiteur);
        $ficheFraisProchaine = $em->getRepository(Fichefrais::class)->findFichefraisProchaine($visiteur);

        if ($ficheFrais == $ficheFraisCourante || !in_array($ficheFrais, $fichesFrais)) {
            throw $this->createAccessDeniedException();
        }

        $ligneFraisHorsForfaits = $em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($ficheFrais);
        $ligneFraisForfaits = $em->getRepository(Lignefraisforfait::class)->findByFichefrais($ficheFrais);
        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();

        $formFicheFrais = $this->createForm(FichefraisType::class, new Fichefrais());
        $formFicheFrais->handleRequest($request);

        $formLigneFraisHorsForfait = $this->createForm(LignefraishorsforfaitType::class, new Lignefraishorsforfait());
        $formLigneFraisHorsForfait->handleRequest($request);

        if ($formFicheFrais->isSubmitted() || $formLigneFraisHorsForfait->isSubmitted()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('visiteur/consulter_detail_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'formLigneFraisHorsForfait' => $formLigneFraisHorsForfait->createView(),
            'formFicheFrais' => $formFicheFrais->createView(),
            'ficheFrais' => $ficheFrais,
            'ficheFraisProchaine' => $ficheFraisProchaine,
            'ligneFraisForfaits' => $ligneFraisForfaits,
            'ligneFraisHorsForfaits' => $ligneFraisHorsForfaits,
            'fraisForfaits' => $fraisForfaits
        ]);
    }

    public function supprimerFraisHorsForfait(Lignefraishorsforfait $ligneFraisHorsForfait, Request $request): Response
    {
        $visiteur = $this->getUser();

        $ficheFraisCourante = $this->em->getRepository(Fichefrais::class)->findFichefraisCourante($visiteur);
        $ficheFraisProchaine = $this->em->getRepository(Fichefrais::class)->findFichefraisProchaine($visiteur);

        if (
            $ligneFraisHorsForfait->getMois() != $ficheFraisCourante->getMois() &&
            $ligneFraisHorsForfait->getMois() != $ficheFraisProchaine->getMois()
        ) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $ligneFraisHorsForfait->getId(), $request->request->get('supprimer_frais_hors_forfait_token'))) {
            $this->em->remove($ligneFraisHorsForfait);
            $this->em->flush();
            $this->addFlash('ligneFraisHorsForfaitSupprime', 'Le frais hors forfait a bien été supprimé.');
        }

        if ($request->attributes->get('ligneFraisHorsForfait')->getIdFicheFrais() == $ficheFraisProchaine) {
            return $this->redirectToRoute('consulter_detail_fiche_frais', [
                'idFicheFrais' => $ficheFraisProchaine->getId()
            ]);
        }

        return $this->redirectToRoute('saisir_fiche_frais');
    }

    private function creerFichefrais($visiteur)
    {
        $fraisForfaits = $this->em->getRepository(Fraisforfait::class)->findAllAsc();
        $derniereFicheFrais = $this->em->getRepository(Fichefrais::class)->findOneBy(array('idVisiteur' => $visiteur), array('mois' => 'DESC'));

        if ($derniereFicheFrais) {
            $etatCloturee = $this->em->getRepository(Etat::class)->find('CL');
            $derniereFicheFrais->setIdEtat($etatCloturee);
        }

        $ficheFrais = new ficheFrais();
        $ficheFrais->setIdVisiteur($visiteur);
        $ficheFrais->setIdEtat($this->em->getRepository(Etat::class)->find('CR'));
        $this->em->persist($ficheFrais);

        $i = 0;
        foreach ($ficheFrais->getLignefraisforfaits() as $ligneFraisForfait) {
            $ligneFraisForfait->setFicheFrais($ficheFrais);
            $ligneFraisForfait->setFraisForfait($fraisForfaits[$i]);
            $ligneFraisForfait->setQuantite(0);
            $this->em->persist($ligneFraisForfait);
            $i++;
        }
        $this->em->flush();

        return $ficheFrais;
    }

    private function reporterLigneFraisHorsForfait($ligneFraisHorsForfait)
    {
        $visiteur = $this->getUser();

        $fraisForfaits = $this->em->getRepository(Fraisforfait::class)->findAllAsc();
        $ficheFrais = $this->em->getRepository(Fichefrais::class)->findFichefraisProchaine($visiteur);

        if (!$ficheFrais) {
            $ficheFrais = new ficheFrais();
        }

        $ficheFrais->setIdVisiteur($this->getUser());
        $ficheFrais->setIdEtat($this->em->getRepository(Etat::class)->find('CR'));
        $ficheFrais->setMois((new \DateTime())->modify('+1 month'));
        $ligneFraisHorsForfait->setMois((new \DateTime())->modify('+1 month'));
        $this->em->persist($ficheFrais);

        $i = 0;
        foreach ($ficheFrais->getLignefraisforfaits() as $ligneFraisForfait) {
            $ligneFraisForfait->setFicheFrais($ficheFrais);
            $ligneFraisForfait->setFraisForfait($fraisForfaits[$i]);
            $ligneFraisForfait->setQuantite(0);
            $this->em->persist($ligneFraisForfait);
            $i++;
        }

        $statutAttente = $this->em->getRepository(Statut::class)->find('ATT');
        $ligneFraisHorsForfait->setIdStatut($statutAttente);
        $ligneFraisHorsForfait->setIdVisiteur($ficheFrais->getIdVisiteur()->getId());
        $ligneFraisHorsForfait->setIdFicheFrais($ficheFrais);

        $ligneFraisHorsForfait->setMois((new \DateTime())->modify('+1 month'));
        $this->em->persist($ligneFraisHorsForfait);

        $this->em->flush();

        return $ligneFraisHorsForfait;
    }
}
