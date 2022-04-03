<?php

namespace App\Repository;

use App\Entity\GuitarSpec;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GuitarSpec|null find($id, $lockMode = null, $lockVersion = null)
 * @method GuitarSpec|null findOneBy(array $criteria, array $orderBy = null)
 * @method GuitarSpec[]    findAll()
 * @method GuitarSpec[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuitarSpecRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuitarSpec::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(GuitarSpec $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(GuitarSpec $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return GuitarSpec[] Returns an array of GuitarSpec objects
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
    public function findOneBySomeField($value): ?GuitarSpec
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
