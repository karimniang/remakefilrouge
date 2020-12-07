<?php

namespace App\Repository;

use App\Entity\StatutBriefPromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatutBriefPromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutBriefPromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutBriefPromo[]    findAll()
 * @method StatutBriefPromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutBriefPromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutBriefPromo::class);
    }

    // /**
    //  * @return StatutBriefPromo[] Returns an array of StatutBriefPromo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatutBriefPromo
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
