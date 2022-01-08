<?php

namespace App\Repository;

use App\Entity\MerchCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MerchCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MerchCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MerchCategory[]    findAll()
 * @method MerchCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MerchCategory::class);
    }

    // /**
    //  * @return MerchCategory[] Returns an array of MerchCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MerchCategory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
