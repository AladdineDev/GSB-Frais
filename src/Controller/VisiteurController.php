<?php

namespace App\Controller;

use App\Entity\Etat;
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
        $ligneFraisForfaits = $em->getRepository(Lignefraisforfait::class)->findByFichefrais($ficheFrais);
        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();

        if (!$ficheFrais) {
            $ficheFrais = $this->creerFichefrais($ficheFrais, $fraisForfaits, $visiteur);
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

            return $this->redirectToRoute('saisir_fiche_frais');
        }

        if ($formFraisHorsForfait->isSubmitted() && $formFraisHorsForfait->isValid()) {
            $fraisHorsForfaitInstance->setIdvisiteur($ficheFrais->getIdvisiteur()->getId());
            $fraisHorsForfaitInstance->setMois($ficheFrais->getMois());
            $fraisHorsForfaitInstance->setIdfichefrais($ficheFrais);
            $em->persist($formFraisHorsForfait->getData());
            $em->flush();

            return $this->redirectToRoute('saisir_fiche_frais');
        }

        return $this->render('visiteur/saisir_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'formFraisHorsForfait' => $formFraisHorsForfait->createView(),
            'formFicheFrais' => $formFicheFrais->createView(),
            'ficheFrais' => $ficheFrais,
            'ligneFraisForfaits' => $ligneFraisForfaits,
            'fraisHorsForfaits' => $fraisHorsForfaits,
            'fraisForfaits' => $fraisForfaits
        ]);
    }

    public function consulterFichesFrais(FichefraisRepository $ficheFraisRepository, EntityManagerInterface $em): Response
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
        $fraisHorsForfaits = $em->getRepository(Lignefraishorsforfait::class)->findByFichefrais($ficheFrais);
        $ligneFraisForfaits = $em->getRepository(Lignefraisforfait::class)->findByFichefrais($ficheFrais);
        $fraisForfaits = $em->getRepository(Fraisforfait::class)->findAllAsc();

        // $ficheFraisInstance = new Fichefrais();
        // $formFicheFrais = $this->createForm(FichefraisType::class, $ficheFraisInstance);
        // $formFicheFrais->handleRequest($request);

        // $fraisHorsForfaitInstance = new Lignefraishorsforfait();
        // $formFraisHorsForfait = $this->createForm(LignefraishorsforfaitType::class, $fraisHorsForfaitInstance);
        // $formFraisHorsForfait->handleRequest($request);

        // if ($formFicheFrais->isSubmitted() && $formFicheFrais->isValid()) {
        //     $i = 0;
        //     foreach ($formFicheFrais->getData()->getLignefraisforfaits() as $ligneFraisForfaitInput) {
        //         $ligneFraisForfaits[$i]->setQuantite($ligneFraisForfaitInput->getQuantite());
        //         $em->persist($ligneFraisForfaits[$i]);
        //         $i++;
        //     }
        //     $em->flush();

        //     return $this->redirectToRoute('consulter_detail_fiche_frais', compact('idFicheFrais'));
        // }

        // if ($formFraisHorsForfait->isSubmitted() && $formFraisHorsForfait->isValid()) {
        //     $fraisHorsForfaitInstance->setIdvisiteur($ficheFrais->getIdvisiteur()->getId());
        //     $fraisHorsForfaitInstance->setMois($ficheFrais->getMois());
        //     $fraisHorsForfaitInstance->setIdfichefrais($ficheFrais);
        //     $em->persist($formFraisHorsForfait->getData());
        //     $em->flush();

        //     return $this->redirectToRoute('consulter_detail_fiche_frais', compact('idFicheFrais'));
        // }

        return $this->render('visiteur/consulter_detail_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            // 'formFraisHorsForfait' => $formFraisHorsForfait->createView(),
            // 'formFicheFrais' => $formFicheFrais->createView(),
            'ficheFrais' => $ficheFrais,
            'ligneFraisForfaits' => $ligneFraisForfaits,
            'fraisHorsForfaits' => $fraisHorsForfaits,
            'fraisForfaits' => $fraisForfaits
        ]);
    }
    //     return $this->redirectToRoute('consulter_detail_fiche_frais', compact('id'));

    private function creerFichefrais($ficheFrais, $fraisForfaits, $visiteur)
    {
        $em = $this->getDoctrine()->getManager();
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

    public function supprimerFraisHorsForfait(Request $request, Lignefraishorsforfait $lignefraishorsforfait): Response
    {
        if ($this->isCsrfTokenValid('delete' . $lignefraishorsforfait->getId(), $request->request->get('supprimer_fraishorsforfait_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lignefraishorsforfait);
            $em->flush();
        }

        return $this->redirectToRoute('saisir_fiche_frais', [], Response::HTTP_SEE_OTHER);
    }
}
