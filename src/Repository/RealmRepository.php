<?php

namespace App\Repository;

use App\Entity\Realm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Realm|null find($id, $lockMode = null, $lockVersion = null)
 * @method Realm|null findOneBy(array $criteria, array $orderBy = null)
 * @method Realm[]    findAll()
 * @method Realm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Realm::class);
    }

    public function truncate()
    {
        $em = $this->getEntityManager();
        $connexion = $em->getConnection();
        $platform = $connexion->getDatabasePlatform();
        $connexion->executeUpdate($platform->getTruncateTableSQL('realm', true));
    }

    // /**
    //  * @return Realm[] Returns an array of Realm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Realm
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
