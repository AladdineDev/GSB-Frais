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

            $randomPhoto = 'https://randomuser.me/api/portraits/men/' . $faker->numberBetween(0, 99) . '.jpg';
            $comptable->setPhoto($randomPhoto);
        }

        foreach ($visiteurs as  $visiteur) {

            $this->hasherMdp($visiteur);

            $randomPhoto = 'https://randomuser.me/api/portraits/men/' . $faker->numberBetween(0, 99) . '.jpg';
            $visiteur->setPhoto($randomPhoto);

            $nbFicheFrais = $faker->numberBetween(0, 15);
            $nbMoisSansFicheFrais = $faker->numberBetween(0, 2);

            for ($i = 0; $i < $nbFicheFrais; $i++) {

                $ficheFrais = $this->creerFichefrais();
                $ficheFrais->setIdVisiteur($visiteur);
                $ficheFrais->setMois($faker->dateTimeBetween(
                    (new \DateTime('now'))->modify('-' . $i . ' month')->format('Y-m-01'),
                    (new \DateTime('now'))->modify('-' . $i . ' month')->format('Y-m-t')
                ));

                if ($i == 0) {
                    $ficheFrais->setIdEtat($this->em->getRepository(Etat::class)->find('CR'));
                } elseif ($i == 1) {
                    if ($faker->boolean()) {
                        $ficheFrais->setIdEtat($this->em->getRepository(Etat::class)->find('CL'));
                    } else {
                        $ficheFrais->setIdEtat($this->em->getRepository(Etat::class)->find('VA'));
                        $ligneFraisForfaits = $ficheFrais->getLignefraisforfaits();
                        foreach ($ligneFraisForfaits as $ligneFraisForfait) {
                            $ficheFrais->setMontantValide(
                                $ficheFrais->getMontantValide() + $ligneFraisForfait->getQuantite() * $ligneFraisForfait->getFraisForfait()->getMontant()
                            );
                            $manager->persist($ligneFraisForfait);
                        }
                    }
                    $ficheFrais->setDateModif($faker->dateTimeBetween(
                        $faker->dateTimeThisMonth('now')->modify('-' . $i . ' month'),
                        (new \DateTime('now'))->modify('-' . $i . ' month')->format('Y-m-t')
                    )->modify('+1 month'));
                } else {
                    $ficheFrais->setIdEtat($this->em->getRepository(Etat::class)->find('RB'));
                    $ligneFraisForfaits = $ficheFrais->getLignefraisforfaits();
                    foreach ($ligneFraisForfaits as $ligneFraisForfait) {
                        $ficheFrais->setMontantValide(
                            $ficheFrais->getMontantValide() + $ligneFraisForfait->getQuantite() * $ligneFraisForfait->getFraisForfait()->getMontant()
                        );
                        $manager->persist($ligneFraisForfait);
                    }
                    $ficheFrais->setDateModif($faker->dateTimeBetween(
                        $ficheFrais->getMois(),
                        (new \DateTime('now'))->modify('-' . $i . ' month')->format('Y-m-t')
                    )->modify('+1 month'));
                    $i += $nbMoisSansFicheFrais;
                }

                $nombreFraisHorsForfait = $faker->numberBetween(0, 8);

                for ($j = 0; $j < $nombreFraisHorsForfait; $j++) {

                    $ligneFraisHorsForfait = $this->creerFraisHorsForfait($ficheFrais);
                    $ligneFraisHorsForfait->setDate($faker->dateTimeThisYear('now')->modify('-' . $i . ' month'));
                    $ligneFraisHorsForfait->setMontant($faker->randomFloat(2, 1, 500));
                    $ligneFraisHorsForfait->setLibelle($faker->randomElement($this->getLibelleFraisHorsForfait()));

                    $statuts = $this->em->getRepository(Statut::class)->findAllAsc();
                    $etatValide = $this->em->getRepository(Etat::class)->find('VA');
                    $etatRembourse = $this->em->getRepository(Etat::class)->find('RB');
                    $etatCree = $this->em->getRepository(Etat::class)->find('CR');

                    if ($ficheFrais->getIdEtat() == $etatValide || $ficheFrais->getIdEtat() == $etatRembourse) {
                        array_shift($statuts);
                    }
                    $ligneFraisHorsForfait->setIdStatut($faker->randomElement($statuts));

                    if (
                        $ligneFraisHorsForfait->getIdstatut() == $this->em->getRepository(Statut::class)->find('VAL') &&
                        ($ficheFrais->getIdEtat() != $etatCree)
                    ) {
                        $ficheFrais->setMontantValide($ficheFrais->getMontantValide() + $ligneFraisHorsForfait->getMontant());
                        $ficheFrais->setNbJustificatifs($ficheFrais->getNbJustificatifs() + 1);
                    }
                    $manager->persist($ligneFraisHorsForfait);
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
            $i++;
        }
        $this->em->flush();

        return $ficheFrais;
    }

    private function creerFraisHorsForfait($ficheFrais)
    {
        $ligneFraisHorsForfait = new Lignefraishorsforfait;
        $ligneFraisHorsForfait->setIdFicheFrais($ficheFrais);

        return $ligneFraisHorsForfait;
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
            "participation à une conférence privée sur la pharmacovigilance",
            "organisation de cocktail",
            "réunion d'information"
        );
    }
}
