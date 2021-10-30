<?php

namespace App\Repository;

use App\Entity\Lignefraisforfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lignefraisforfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lignefraisforfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lignefraisforfait[]    findAll()
 * @method Lignefraisforfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignefraisforfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lignefraisforfait::class);
    }

    // /**
    //  * @return Lignefraisforfait[] Returns an array of Lignefraisforfait objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lignefraisforfait
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByFichefrais($idvisiteur)
    {
        $dql = 'SELECT Lff
                FROM App:Lignefraisforfait Lff
                JOIN App:Fichefrais Fichefrais
                WITH Fichefrais = Lff.ficheFrais
                JOIN App:Fraisforfait Fraisforfait
                WITH Fraisforfait = Lff.fraisForfait
                WHERE Fichefrais.idvisiteur = :idvisiteur
                ORDER BY Lff.id ASC';

        $query = $this->_em->createQuery($dql);
        $query->setParameter('idvisiteur', $idvisiteur);

        return $query->getResult();
    }
}
