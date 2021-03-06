<?php

namespace App\Repository;

use App\Entity\LostPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LostPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method LostPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method LostPassword[]    findAll()
 * @method LostPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LostPasswordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LostPassword::class);
    }

    # Requête pour chercher le token et l'ID dans la BDD
    public function searchToken(string $token, int $idUser)
    {
        $querybuilder = $this->createQueryBuilder('l')
            ->andWhere('l.token = :token','l.user = :userId')
            ->setParameter('token', $token)
            ->setParameter('userId',$idUser)
            ->getQuery();
        return $querybuilder->execute();
    }


//    /**
//     * @return LostPassword[] Returns an array of LostPassword objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LostPassword
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
