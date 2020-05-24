<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class BankController extends DefaultController
{

    public function bank():JsonResponse
    {
        $result = [
            'token'    => 'foo',
        ];

        return $this->json($result);
    }
}