<?php

namespace App\Command;

use App\Entity\Etat;
use App\Entity\Fichefrais;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClotureFicheFraisCommand extends Command
{

    private $em;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:fichefrais:cloturer';

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        $fichesFrais = $this->em->getRepository(Fichefrais::class)->findAll();
        $etatCreee = $this->em->getRepository(Etat::class)->find('CR');
        $etatCloturee = $this->em->getRepository(Etat::class)->find('CL');

        foreach ($fichesFrais as $ficheFrais) {
            if ($ficheFrais->getIdEtat() == $etatCreee) {
                if ($ficheFrais->getMois()->format('m') < (new \DateTime('now'))->format('m')) {
                    $ficheFrais->setIdEtat($etatCloturee);
                    $this->em->persist($ficheFrais);
                }
            }
        }
        $this->em->flush();

        $output->writeln('Toutes les fiches de frais des mois précédents ont bien été clôturées ! ');

        return Command::SUCCESS;
    }
}
