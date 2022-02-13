<?php

namespace App\Repository;

use App\Entity\GuitarVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GuitarVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuitarVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuitarVariant[]    findAll()
 * @method GuitarVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuitarVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuitarVariant::class);
    }

    // /**
    //  * @return GuitarVariants[] Returns an array of GuitarVariants objects
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
    public function findOneBySomeField($value): ?GuitarVariants
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
