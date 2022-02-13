<?php

namespace App\Repository;

use App\Entity\GuitarModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GuitarModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuitarModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuitarModel[]    findAll()
 * @method GuitarModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuitarModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuitarModel::class);
    }

    // /**
    //  * @return GuitarModel[] Returns an array of GuitarModel objects
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
    public function findOneBySomeField($value): ?GuitarModel
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
