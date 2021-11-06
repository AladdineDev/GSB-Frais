<?php

namespace App\Repository;

use App\Entity\Lignefraishorsforfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lignefraishorsforfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lignefraishorsforfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lignefraishorsforfait[]    findAll()
 * @method Lignefraishorsforfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignefraishorsforfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lignefraishorsforfait::class);
    }

    // /**
    //  * @return Lignefraishorsforfait[] Returns an array of Lignefraishorsforfait objects
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
    public function findOneBySomeField($value): ?Lignefraishorsforfait
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByFichefrais($fichefrais)
    {
        $dql = 'SELECT Lfhf
                FROM App:Lignefraishorsforfait Lfhf
                JOIN App:Fichefrais Fichefrais
                WITH Fichefrais = Lfhf.idfichefrais
                WHERE Fichefrais = :fichefrais
                ORDER BY Lfhf.date DESC, Lfhf.montant ASC';

        $query = $this->_em->createQuery($dql);
        $query->setParameter('fichefrais', $fichefrais);

        return $query->getResult();
    }
}
