<?php

namespace App\Controller;

use App\Repository\AmountRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class BankController extends DefaultController
{

    /** @var AmountRepository */
    private $amountRepository;

    public function __construct(AmountRepository $amountRepository)
    {
        $this->amountRepository = $amountRepository;
    }

    public function bank(): JsonResponse
    {
        $amounts = $this->amountRepository->findBy(['isActive' => true]);

        $result = [];
        foreach ($amounts as $amount) {
            $result[] = [
                'currency' => $amount->currency()->getCode(),
                'amount'   => $amount->getAmount(),
            ];
        }

        return $this->json(['amounts' => $result]);
    }
}