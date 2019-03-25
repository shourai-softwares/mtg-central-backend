<?php

namespace App\Repository;

use App\Entity\Card;
use App\Entity\User;
use App\Entity\UserCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCard[]    findAll()
 * @method UserCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserCard::class);
    }

    // /**
    //  * @return UserCard[] Returns an array of UserCard objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findInUserCollection(User $user, Card $card): ?UserCard
    {
        return $this->createQueryBuilder('uc')
            ->andWhere('uc.user = :user')
            ->andWhere('uc.card = :card')
            ->setParameter('user', $user)
            ->setParameter('card', $card)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
