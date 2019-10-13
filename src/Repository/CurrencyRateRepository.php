<?php

namespace App\Repository;

use App\Entity\CurrencyRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CurrencyRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyRate[]    findAll()
 * @method CurrencyRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyRate::class);
    }

    public function deleteCurrencyRates(\DateTimeInterface $date, string $source)
    {
        $dql = 'DELETE FROM App\Entity\CurrencyRate c WHERE c.source = :source AND c.date BETWEEN :dateStart AND :dateEnd';

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters([
            'dateStart' => $date->setTime(0, 0)->format('Y-m-d H:i:s'),
            'dateEnd'   => $date->setTime(23, 59)->format('Y-m-d H:i:s'),
            'source'    => $source
        ]);

        return $query->execute();
    }
}
