<?php

namespace App\Infrastructure\Repository;

use App\Model\Entity\Characteristic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Characteristic>
 */
class CharacteristicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Characteristic::class);
    }

    /**
     * @return Characteristic Returns an array of Characteristic objects
     */
    public function findByID($value): Characteristic
    {
        return $this->find($value)
        ;
    }

//    public function findOneBySomeField($value): ?Characteristic
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
