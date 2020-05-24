<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class ConversionController extends DefaultController
{

    public function conversions():JsonResponse
    {
        return $this->json(['foo' => 'bar']);
    }

    public function conversion(int $id):JsonResponse
    {
        return $this->json(['foo' => $id]);
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