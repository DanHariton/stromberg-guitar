<?php

namespace App\Repository;

use App\Entity\GuitarColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GuitarColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuitarColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuitarColor[]    findAll()
 * @method GuitarColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuitarColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuitarColor::class);
    }

    // /**
    //  * @return GuitarColor[] Returns an array of GuitarColor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GuitarColor
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
