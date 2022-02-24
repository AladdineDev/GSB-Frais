<?php

namespace App\Repository;

use App\Entity\Fichefrais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fichefrais|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fichefrais|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fichefrais[]    findAll()
 * @method Fichefrais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichefraisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fichefrais::class);
    }

    // /**
    //  * @return Fichefrais[] Returns an array of Fichefrais objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fichefrais
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findFichefraisCourante($visiteur)
    {
        $dql = 'SELECT Fichefrais
                FROM App:Fichefrais Fichefrais
                WHERE Fichefrais.idVisiteur = :visiteur
                AND Fichefrais.mois BETWEEN :moisDebut AND :moisFin';

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'visiteur' => $visiteur,
            'moisDebut' => (new \DateTime())->format('Y-m-01'),
            'moisFin' => (new \DateTime())->format('Y-m-t')
        ]);

        return $query->getOneOrNullResult();
    }

    public function findFichesfrais($visiteur)
    {
        $dql = 'SELECT Fichefrais
                FROM App:Fichefrais Fichefrais
                WHERE Fichefrais.idVisiteur = :visiteur
                AND Fichefrais.mois BETWEEN :premierMois AND :dernierMois
                ORDER BY Fichefrais.mois DESC';

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'visiteur' => $visiteur,
            'premierMois' => (new \DateTime())->modify('-1 year')->format('Y-m-01'),
            'dernierMois' => (new \DateTime())->modify('+1 month')->format('Y-m-t')
        ]);

        return $query->getResult();
    }

    public function findFichesfraisSaisies($visiteur)
    {
        $dql = 'SELECT Fichefrais
                FROM App:Fichefrais Fichefrais
                WHERE Fichefrais.idVisiteur = :visiteur
                AND Fichefrais.mois BETWEEN :premierMois AND :dernierMois
                ORDER BY Fichefrais.mois DESC';

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'visiteur' => $visiteur,
            'premierMois' => (new \DateTime())->modify('-1 year')->format('Y-m-01'),
            'dernierMois' => (new \DateTime())->modify('-1 month')->format('Y-m-t')
        ]);

        return $query->getResult();
    }

    public function findFichefrais($idFicheFrais)
    {
        $dql = 'SELECT Fichefrais
                FROM App:Fichefrais Fichefrais
                WHERE Fichefrais.id = :idFicheFrais
                AND Fichefrais.mois BETWEEN :premierMois AND :dernierMois';

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'idFicheFrais' => $idFicheFrais,
            'premierMois' => (new \DateTime())->modify('-1 year')->format('Y-m-01'),
            'dernierMois' => (new \DateTime())->modify('+1 month')->format('Y-m-t')
        ]);

        return $query->getOneOrNullResult();
    }

    public function findFichefraisProchaine($visiteur)
    {
        $dql = 'SELECT Fichefrais
                FROM App:Fichefrais Fichefrais
                WHERE Fichefrais.idVisiteur = :visiteur
                AND Fichefrais.mois BETWEEN :premierMois AND :dernierMois
                ORDER BY Fichefrais.mois DESC';

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'visiteur' => $visiteur,
            'premierMois' => (new \DateTime())->modify('+1 month')->format('Y-m-01'),
            'dernierMois' => (new \DateTime())->modify('+1 month')->format('Y-m-t')
        ]);

        return $query->getOneOrNullResult();
    }

    public function findByVisiteur($visiteur)
    {
        $dql = 'SELECT Fichefrais
                FROM App:Fichefrais Fichefrais
                WHERE Fichefrais.idVisiteur = :visiteur
                AND Fichefrais.mois BETWEEN :premierMois AND :dernierMois
                ORDER BY Fichefrais.mois DESC';

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'visiteur' => $visiteur,
            'premierMois' => (new \DateTime())->modify('-1 year')->format('Y-m-01'),
            'dernierMois' => (new \DateTime())->format('Y-m-t')
        ]);

        return $query->getResult();
    }
}
