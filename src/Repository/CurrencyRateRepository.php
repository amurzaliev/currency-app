<?php

namespace App\Repository;

use App\Entity\CurrencyRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method CurrencyRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyRate[]    findAll()
 * @method CurrencyRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRateRepository extends ServiceEntityRepository
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ManagerRegistry $registry, ParameterBagInterface $params)
    {
        parent::__construct($registry, CurrencyRate::class);
        $this->params = $params;
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

    /**
     * @param string $from
     * @param string $to
     * @param int $value
     * @return bool|float|int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function convertCurrency(string $from, string $to, int $value)
    {
        /** @var CurrencyRate $fromRate */
        $fromRate = $this->createQueryBuilder('c')
            ->where('c.code = :from')
            ->setParameter('from', $from)
            ->andWhere('c.source = :source')
            ->setParameter('source', $this->params->get('data_source'))
            ->orderBy('c.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        /** @var CurrencyRate $toRate */
        $toRate = $this->createQueryBuilder('c')
            ->where('c.code = :to')
            ->setParameter('to', $to)
            ->andWhere('c.source = :source')
            ->setParameter('source', $this->params->get('data_source'))
            ->orderBy('c.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        if ($fromRate && $toRate) {
            return $value / $fromRate->getRate() * $toRate->getRate();
        }

        return false;
    }
}
