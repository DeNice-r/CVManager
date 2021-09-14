<?php

namespace App\Repository;

use App\Entity\CVReactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CVReactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method CVReactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method CVReactions[]    findAll()
 * @method CVReactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CVReactionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CVReactions::class);
    }

    // /**
    //  * @return CVReactions[] Returns an array of CVReactions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CVReactions
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
