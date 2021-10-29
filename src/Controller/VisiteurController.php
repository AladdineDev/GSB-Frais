<?php

namespace App\Controller;


use App\Entity\Fichefrais;
use App\Entity\Fraisforfait;
use App\Entity\Lignefraisforfait;
use App\Entity\Lignefraishorsforfait;
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
    public function index(): Response
    {
        return $this->render('visiteur/index.html.twig', [
            'controller_name' => 'VisiteurController',
        ]);
    }

    public function saisirFicheFrais(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        $fraisForfait = $entityManager->getRepository(Fraisforfait::class)->findAll();
        $fraisHorsForfait = $entityManager->getRepository(Lignefraishorsforfait::class)->findAll();

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

        // dd($formFraisForfait);
        // dd($ligneFraisForfaitInstance);

        if ($formFraisHorsForfait->isSubmitted() && $formFraisHorsForfait->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $formFraisHorsForfait->getData();
            $entityManager->persist($task);
            $entityManager->flush();
        }
        // dd($formFichefrais);
        // dd($formFraisHorsForfait);
        // dd($fraisForfait);

        return $this->render('visiteur/saisir_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'formFichefrais' => $formFichefrais->createView(),
            'formLigneFraisForfait' => $formLigneFraisForfait->createView(),
            'formFraisHorsForfait' => $formFraisHorsForfait->createView(),
            'formFraisForfait' => $formFraisForfait->createView(),
            'fraisForfait' => $fraisForfait,
            'fraisHorsForfait' => $fraisHorsForfait
        ]);
    }

    public function consulterFicheFrais(FichefraisRepository $ficheFraisRepository): Response
    {

        $fichesFrais = $ficheFraisRepository->findAll();


        return $this->render('visiteur/consulter_fiche_frais.html.twig', [
            'controller_name' => 'VisiteurController',
            'fichesFrais' => $fichesFrais
        ]);
    }
}
