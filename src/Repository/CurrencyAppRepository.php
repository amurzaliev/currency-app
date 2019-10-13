<?php

namespace App\Repository;

use App\Entity\CurrencyApp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CurrencyApp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyApp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyApp[]    findAll()
 * @method CurrencyApp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyAppRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyApp::class);
    }

    // /**
    //  * @return CurrencyApp[] Returns an array of CurrencyApp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CurrencyApp
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
