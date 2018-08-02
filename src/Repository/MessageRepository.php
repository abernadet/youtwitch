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

    public function countMail2($user)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT COUNT(m.id) FROM message m
        WHERE (m.lu IS NULL OR m.lu = 0)
        AND recipient_id = :iduser
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['iduser' => $user->getId()]);

        // returns an array of arrays (i.e. a raw data set)
        $rez = $stmt->fetch();
        return $rez['COUNT(m.id)'];
    }

    public function findConvMessages(User $user1, User $user2)
    {
        $bdd = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * 
        FROM message m 
        WHERE (m.sender_id = :user1_id OR m.recipient_id = :user1_id) 
        AND (m.sender_id = :user2_id OR m.recipient_id = :user2_id)
        ';  

        $stmt = $bdd->prepare($sql);
        $stmt->execute(['user1_id' => $user1->getId(), 'user2_id' => $user2->getId()]);

        $result = $stmt->fetch();
        return $result;
    }

    /*
    public function findBySender(int $user1, int $user2){
        $querybuilder = $this->createQueryBuilder('m')
            ->andWhere('m.recipient_id = :user1 OR m.recipient_id = :user2')
            ->andWhere('m.sender_id = :user1 OR m.sender_id = :user2')
            ->setParameter('user1', $user1)
            ->setParameter('user2', $user2)
            ->getQuery();
        return $querybuilder->execute();
    }
*/
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
