<?php

namespace App\Service;

use App\Entity\Amount;
use App\Entity\Conversion;
use App\Entity\Currency;
use App\Repository\ConversionRepository;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PropertyAccess;

class ConversionManager
{

    /** @var ConversionRepository */
    private $conversionRepository;

    /** @var CurrencyRepository  */
    private $currencyRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var PropertyAccess\PropertyAccessor  */
    private $propertyAccessor;

    public function __construct(
        ConversionRepository $conversionRepository,
        CurrencyRepository $currencyRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->conversionRepository = $conversionRepository;
        $this->currencyRepository = $currencyRepository;
        $this->entityManager = $entityManager;
        $this->propertyAccessor = PropertyAccess\PropertyAccess::createPropertyAccessor();
    }

    public function findAllConversionIDs(): array
    {
        $conversions = $this->conversionRepository->findAll();

        $result = [];
        foreach ($conversions as $conversion) {
            $result[] = $conversion->getId();
        }

        return $result;
    }

    public function findConversionById(int $id): ?Conversion
    {
        return $this->conversionRepository->find($id);
    }

    public function storeConversion(Conversion $conversion)
    {
        try {
            $this->entityManager->persist($conversion);
            $this->entityManager->flush();
        } catch (ORMException $e) {
            throw new BadRequestHttpException('Database error');
        }
    }

    public function prepareConversion(array $data): Conversion
    {
        $fromCurrencyCode = $fromCurrencyCode = $this->propertyAccessor->getValue($data, '[fromAmount][currency]');
        $fromCurrency = $this->currencyRepository->findOneBy(['code' => $fromCurrencyCode]);
        $toCurrencyCode = $this->propertyAccessor->getValue($data, '[toCurrency]');
        $toCurrency = $this->currencyRepository->findOneBy(['code' => $toCurrencyCode]);

        if (empty($fromCurrency) || empty($toCurrency)) {
            throw new HttpException(406, 'Invalid currency pair');
        }

        $fromAmount = (new Amount())
            ->setAmount($this->propertyAccessor->getValue($data, '[fromAmount][amount]'))
            ->setCurrency($fromCurrency)
            ->activate();

        $result = $this->createConversion($fromAmount, $fromCurrency, $toCurrency);

        $this->storeConversion($result);

        return $result;
    }

    public function executeConversion(Conversion $conversion)
    {
        $conversion->execute();
        $this->storeConversion($conversion);
    }

    private function createConversion(
        Amount $amount,
        Currency $fromCurrency,
        Currency $toCurrency
    ): Conversion
    {
        $result = new Conversion();
        $result->setDefaultExpireAt();
        $result->setFromAmount($amount);

        $rate = $this->propertyAccessor->getValue($fromCurrency->getRates(), "[{$toCurrency->getCode()}]");

        if (empty($rate)) {
            throw new HttpException(406, 'Rate doesnt exists');
        }

        $result->setRate($rate);
        $result->setToAmount((new Amount())
            ->setCurrency($toCurrency)
            ->setAmount($result->fromAmount()->getAmount() * $result->rate())
        );

        return $result;
    }
}