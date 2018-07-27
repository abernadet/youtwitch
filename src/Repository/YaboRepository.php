<?php

namespace App\Repository;

use App\Entity\Yabo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Yabo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Yabo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Yabo[]    findAll()
 * @method Yabo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YaboRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Yabo::class);
    }

//    /**
//     * @return Yabo[] Returns an array of Yabo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('y.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Yabo
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
