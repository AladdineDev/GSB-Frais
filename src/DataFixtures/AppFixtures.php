<?php

namespace App\DataFixtures;

use App\Entity\Comptable;
use Faker\Factory;
use App\Entity\Etat;
use App\Entity\Statut;
use App\Entity\Visiteur;
use App\Entity\Fichefrais;
use App\Entity\Fraisforfait;
use App\Entity\Lignefraishorsforfait;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $em;
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance
        $faker = Factory::create('fr_FR');

        $visiteurs = $manager->getRepository(Visiteur::class)->findAll();
        $comptables = $manager->getRepository(Comptable::class)->findAll();

        foreach ($comptables as $comptable) {
            $this->hasherMdp($comptable);
        }

        foreach ($visiteurs as  $visiteur) {

            $this->hasherMdp($visiteur);

            $nbFicheFrais = $faker->numberBetween(0, 15);
            $nbMoisSansFicheFrais = $faker->numberBetween(0, 2);

            for ($i = 0; $i < $nbFicheFrais; $i++) {

                $ficheFrais = $this->creerFichefrais();
                $ficheFrais->setIdvisiteur($visiteur);
                $ficheFrais->setMois($faker->dateTimeBetween(
                    (new \DateTime('now'))->modify('-' . $i . ' month')->format('Y-m-01'),
                    'now -' . $i . ' month'
                ));

                if ($i == 0) {
                    $ficheFrais->setIdetat($this->em->getRepository(Etat::class)->find('CR'));
                } elseif ($i == 1) {
                    if ($faker->boolean()) {
                        $ficheFrais->setIdetat($this->em->getRepository(Etat::class)->find('CL'));
                    } else {
                        $ficheFrais->setIdetat($this->em->getRepository(Etat::class)->find('VA'));
                    }
                    $ficheFrais->setDatemodif($faker->dateTimeBetween(
                        $faker->dateTimeThisMonth('now')->modify('-' . $i . ' month'),
                        'now -' . $i . ' month'
                    )->modify('+1 month'));
                } else {
                    $ficheFrais->setIdetat($this->em->getRepository(Etat::class)->find('RB'));
                    $ficheFrais->setDatemodif($faker->dateTimeBetween(
                        $ficheFrais->getMois(),
                        'now -' . $i . ' month'
                    )->modify('+1 month'));
                    $i += $nbMoisSansFicheFrais;
                }

                $nombreFraisHorsForfait = $faker->numberBetween(0, 8);

                for ($j = 0; $j < $nombreFraisHorsForfait; $j++) {

                    $fraisHorsForfait = $this->creerFraisHorsForfait($ficheFrais);
                    $fraisHorsForfait->setDate($faker->dateTimeThisYear('now')->modify('-' . $i . ' month'));
                    $fraisHorsForfait->setMontant($faker->randomFloat(2, 1, 500));
                    $fraisHorsForfait->setLibelle($faker->randomElement($this->getLibelleFraisHorsForfait()));

                    $statuts = $this->em->getRepository(Statut::class)->findAllAsc();
                    $statutValide = $this->em->getRepository(Etat::class)->find('VA');
                    $statutRembourse = $this->em->getRepository(Etat::class)->find('RB');

                    if ($ficheFrais->getIdetat() == $statutValide || $ficheFrais->getIdetat() == $statutRembourse) {
                        array_shift($statuts);
                    }
                    $fraisHorsForfait->setIdstatut($faker->randomElement($statuts));

                    if ($fraisHorsForfait->getIdstatut() == $this->em->getRepository(Statut::class)->find('VAL')) {
                        $ficheFrais->setMontantvalide($ficheFrais->getMontantvalide() + $fraisHorsForfait->getMontant());
                        $ficheFrais->setNbjustificatifs($ficheFrais->getNbjustificatifs() + 1);
                    }
                    $manager->persist($fraisHorsForfait);
                }
                $manager->persist($ficheFrais);
            }
        }
        $manager->flush();
    }

    private function creerFichefrais()
    {
        $faker = Factory::create();
        $fraisForfaits = $this->em->getRepository(Fraisforfait::class)->findAllAsc();

        $ficheFrais = new ficheFrais();

        $i = 0;
        foreach ($ficheFrais->getLignefraisforfaits() as $ligneFraisForfait) {
            $ligneFraisForfait->setFicheFrais($ficheFrais);
            $ligneFraisForfait->setFraisForfait($fraisForfaits[$i]);
            $ligneFraisForfait->setQuantite($faker->numberBetween(0, 30));
            $this->em->persist($ligneFraisForfait);
            $i++;
        }
        $this->em->flush();

        return $ficheFrais;
    }

    private function creerFraisHorsForfait($ficheFrais)
    {
        $fraisHorsForfait = new Lignefraishorsforfait;

        $fraisHorsForfait->setIdvisiteur($ficheFrais->getIdvisiteur()->getId());
        $fraisHorsForfait->setMois($ficheFrais->getMois());
        $fraisHorsForfait->setIdfichefrais($ficheFrais);

        return $fraisHorsForfait;
    }

    private function hasherMdp($users)
    {
        $plaintextPassword = $users->getPassword();

        $hashedPassword = $this->encoder->hashPassword(
            $users,
            $plaintextPassword
        );
        $users->setPassword($hashedPassword);
    }

    private function getLibelleFraisHorsForfait()
    {
        return array(
            "repas en présence d'un spécialiste lors d'une animation",
            "achat de fournitures",
            "réservation de salle pour une conférence",
            "participation à un séminaire d'information scientifique",
            "formation aux techniques commerciales",
            "abonnement à un logiciel de rédaction de comptes-rendus",
            "accident de la route",
            "participation à une conférence privée sur la pharmacovigilance"
        );
    }
}
