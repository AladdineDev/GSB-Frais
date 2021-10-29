<?php

namespace App\Repository;

use App\Entity\Fichefrais;
use DateTime;
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

    // -- SELECTION DE LA FICHE DU MOIS COURANT
    // -- DU VISITEUR COURANT
    // SELECT *
    // FROM FicheFrais
    //     INNER JOIN LigneFraisForfait AS LFF ON FicheFrais.id = LFF.fiche_frais_id
    //     INNER JOIN FraisForfait ON FraisForfait.id = LFF.frais_forfait_id
    //     INNER JOIN LigneFraisHorsForfait AS LFHF ON FicheFrais.id = LFHF.idFicheFrais
    // WHERE FicheFrais.mois = MONTH('2021-08-00')
    //     AND FicheFrais.idVisiteur = 3;

    // --------------------------------------------------

    // public function findFichefraisCourante0($visiteur)
    // {
    // $dql = 'SELECT Fichefrais, Lff, Fraisforfait, Lfhf
    //             FROM App:Fichefrais Fichefrais
    //             JOIN App:Lignefraisforfait Lff
    //             WITH Fichefrais = Lff.ficheFrais
    //             JOIN App:Fraisforfait Fraisforfait
    //             WITH Fraisforfait = Lff.fraisForfait
    //             JOIN App:Lignefraishorsforfait Lfhf
    //             WITH Fichefrais = Lfhf.idfichefrais
    //             WHERE Fichefrais.idvisiteur = :idvisiteur';

    //     $query = $this->_em->createQuery($dql);
    //     $query->setParameter('visiteur', $visiteur);

    //     return $query->getOneOrNullResult();
    //     return $query->getResult();
    // }

    // public function findOneFichefraisCourante($idvisiteur)
    // {
    //     $dql = 'SELECT Fichefrais
    //             FROM App:Fichefrais Fichefrais
    //             WHERE Fichefrais.idvisiteur = :idvisiteur';

    //     $query = $this->_em->createQuery($dql);
    //     $query->setParameter('idvisiteur', $idvisiteur);

    //     // dd($query->getResult());
    //     return $query->getResult();
    // }

    public function findFichefraisCourante($idvisiteur)
    {
        $dql = 'SELECT Fichefrais
                FROM App:Fichefrais Fichefrais
                -- JOIN App:Lignefraisforfait Lff
                -- WITH Fichefrais = Lff.ficheFrais
                -- JOIN App:Fraisforfait Fraisforfait
                -- WITH Fraisforfait = Lff.fraisForfait
                -- JOIN App:Lignefraishorsforfait Lfhf
                -- WITH Fichefrais = Lfhf.idfichefrais
                WHERE Fichefrais.idvisiteur = :idvisiteur
                AND Fichefrais.mois BETWEEN :moisDebut AND :moisFin';

        $query = $this->_em->createQuery($dql);
        $query->setParameters([
            'idvisiteur' => $idvisiteur,
            'moisDebut' => (new DateTime())->format('Y-m-01'),
            'moisFin' => (new DateTime())->format('Y-m-t')
        ]);

        // dd($query->getResult());
        return $query->getOneOrNullResult();
    }

    // public function findFichefraisCourante($idvisiteur)
    // {
    //     $dql = 'SELECT Fichefrais
    //             FROM App:Fichefrais Fichefrais
    //             JOIN App:Lignefraisforfait Lff
    //             WITH Fichefrais = Lff.ficheFrais
    //             JOIN App:Fraisforfait Fraisforfait
    //             WITH Fraisforfait = Lff.fraisForfait
    //             JOIN App:Lignefraishorsforfait Lfhf
    //             WITH Fichefrais = Lfhf.idfichefrais
    //             WHERE Fichefrais.idvisiteur = :idvisiteur';

    //     $query = $this->_em->createQuery($dql);
    //     $query->setParameter('idvisiteur', $idvisiteur);

    //     // dd($query->getResult());
    //     return $query->getOneOrNullResult();
    // }
}
