<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }
//champ lu de type boolean, message false n'a pas été lu, quand la personne va lire le message je le mettrai a true.
    public function countMail($user){
        $count_dql = $this->createQueryBuilder('c')
            ->select('count(c.contenu)')
            ->andWhere('c.lu != 1')
            ->andWhere('c.recipient = :user')
            ->setParameter('user', $user);
        $flag_count = $count_dql->getQuery();
        return $flag_count->getSingleScalarResult();
    }

    public function findBySujet(Message $message){
        $querybuilder = $this->createQueryBuilder('c')
            ->select('c.sujet', 'c.sender_id', 'c.recipient_id', 'c.dateenvoi')
            ->andWhere('c.sujet = :sujet', 'c.sender_id = :sender_id', 'c.recipient_id = :recipient_id', 'c.dateenvoi = :dateenvoi')
            ->setParameter('sujet', $message)
            ->setParameter('sender_id', $message)
            ->setParameter('$recipient_id', $message)
            ->orderBy('dateenvoie', 'DESC')
            ->getQuery();
        return $querybuilder->execute();
    }

//    /**
//     * @return Message[] Returns an array of Message objects
//     */
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
    public function findOneBySomeField($value): ?Message
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
