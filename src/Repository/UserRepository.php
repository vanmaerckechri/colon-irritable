<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\Query;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function isUserFanRecette(string $idUser, string $idRecette)
    {
        $result = $this->createQueryBuilder('u')
            ->innerJoin('u.favoris', 'r')
            ->innerJoin('r.fans', 't')
            ->andwhere('r.id = :idRecette')
            ->setParameter('idRecette', $idRecette)
            ->andwhere('u.id = :idUser')
            ->setParameter('idUser', $idUser)
            ->getQuery()
            ->getResult();
        ;
        if ($result)
        {
            return true;
        }
        return false;
    }

    public function loadUserByUsername($usernameOrEmail)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :query OR u.mail = :query')
            ->setParameter('query', $usernameOrEmail)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOnNameBy($id)
    {
        return $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->select('u.username')
            ->getQuery()
            ->getResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    // /**
    //  * @return User[] Returns an array of User objects
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

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
