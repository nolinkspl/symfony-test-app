<?php

namespace App\Controller;

use App\Entity\Amount;
use App\Entity\Conversion;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConversionController extends DefaultController
{

    public function conversions():JsonResponse
    {
        return $this->json(['foo' => 'bar']);
    }

    public function conversion(int $id):JsonResponse
    {
        /** @var Amount $amount */
        $amount = $this->getDoctrine()
                       ->getRepository(Amount::class)
                       ->find($id);

        var_dump($amount->currency()->getId());

        /** @var Conversion $result */
        $result = $this->getDoctrine()
                       ->getRepository(Conversion::class)
                       ->find($id);

        if ($result === null) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        var_dump($result->amount()->getId());

        return $this->json($result->info());
    }

    public function executeConversion(int $id):JsonResponse
    {
        return $this->json(['foo' => $id]);
    }

    public function prepareConversion(int $id):JsonResponse
    {
        return $this->json(['foo' => $id]);
    }
}