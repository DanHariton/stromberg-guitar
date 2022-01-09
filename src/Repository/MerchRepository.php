<?php

namespace App\Repository;

use App\Entity\Merch;
use App\Entity\MerchCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Merch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Merch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Merch[]    findAll()
 * @method Merch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Merch::class);
    }

    /**
     * @param MerchCategory $category
     * @return Merch[]
     */
    public function findAllEnabledByCategory(MerchCategory $category): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.merchCategory = :category')
            ->setParameter('category', $category)
            ->andWhere('m.enabled = 1')
            ->getQuery()
            ->execute();
    }
}
