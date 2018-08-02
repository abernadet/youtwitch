<?php

namespace App\Repository;

use App\Entity\TrendingSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TrendingSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrendingSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrendingSearch[]    findAll()
 * @method TrendingSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrendingSearchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrendingSearch::class);
    }

//    /**
//     * @return TrendingSearch[] Returns an array of TrendingSearch objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrendingSearch
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
