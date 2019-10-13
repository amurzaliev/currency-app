<?php

namespace App\Controller;

use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CurrencyRateController extends AbstractFOSRestController
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @Rest\Get("/api/convert")
     *
     * @param Request $request
     * @param CurrencyRateRepository $currencyRateRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function getConvert(Request $request, CurrencyRateRepository $currencyRateRepository)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $value = $request->query->getInt('value');

        if (!$from) {
            return $this->handleError([10 => '"From" param is required.'], Response::HTTP_BAD_REQUEST);
        }

        if (!$to) {
            return $this->handleError([10 => '"To" param is required.'], Response::HTTP_BAD_REQUEST);
        }

        if (!$value) {
            return $this->handleError([10 => '"Value" param is required.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $currencyRateRepository->convertCurrency($from, $to, $value);
        } catch (NoResultException $e) {
            return $this->handleError([2 => 'Currency is not found.'], Response::HTTP_NOT_FOUND);
        }

        if (!$result) {
            return $this->handleError([1 => 'Something went wrong while conversion.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $view = $this
            ->view([
                'source'         => $this->params->get('data_source'),
                'from'           => $from,
                'to'             => $to,
                'value'          => $value,
                'convertedValue' => $result,
            ], 200)
            ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * Handle error response
     *
     * @param array $errors
     * @param int $code
     * @return Response
     */
    private function handleError(array $errors, int $code)
    {
        $view = $this
            ->view([
                'errors' => $errors
            ], $code)
            ->setFormat('json');

        return $this->handleView($view);
    }
}
